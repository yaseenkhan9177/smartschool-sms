const CACHE_NAME = "sms-cache-v2";
const urlsToCache = [
    "/",
    "/offline.html",
    "/css/app.css",
    "/js/app.js",
    "/manifest.json",
    "/assets/img/logo-round.jpg",
    "/assets/img/logo.jpg"
];

// Install Event
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then(cache => {
            console.log("[Service Worker] Pre-caching offline page and core assets");
            return cache.addAll(urlsToCache);
        })
        .then(() => self.skipWaiting())
    );
});

// Activate Event (Cleanup old caches)
self.addEventListener("activate", event => {
    console.log("[Service Worker] Activating");
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log("[Service Worker] Deleting old cache:", cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event (Network-first with offline fallback)
self.addEventListener("fetch", event => {
    // We only want to handle GET requests for caching
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
        .then(response => {
            // Optional: dynamically cache visited pages here if desired.
            // For now, we mainly want to ensure we fallback to offline.html if fetch fails
            return response;
        })
        .catch(() => {
            // Fetch failed, try cache
            return caches.match(event.request)
            .then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                // If the request is for an HTML page that isn't cached, show offline page
                if (event.request.headers.get('accept').includes('text/html')) {
                    return caches.match('/offline.html');
                }
            });
        })
    );
});

// Background Sync
self.addEventListener('sync', event => {
    if (event.tag === 'sync-attendance') {
        console.log('[Service Worker] Background Sync triggered for sync-attendance');
        event.waitUntil(syncAttendanceData());
    } else if (event.tag === 'sync-fees') {
        console.log('[Service Worker] Background Sync triggered for sync-fees');
        event.waitUntil(syncFeeData());
    }
});

// Note: Background Sync relies on IndexedDB, which exists in window space. 
// Since Service Worker runs outside the window, we need to manually open IDB here to sync.
async function syncAttendanceData() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('sms-db', 3);

        request.onsuccess = async (event) => {
            const db = event.target.result;
            // Check if object store exists 
            if (!db.objectStoreNames.contains('attendance')) {
                return resolve();
            }

            const transaction = db.transaction('attendance', 'readwrite');
            const store = transaction.objectStore('attendance');
            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = async () => {
                const allData = getAllRequest.result;
                let syncErrors = 0;

                for (const item of allData) {
                    if (!item.synced) {
                        try {
                            const response = await fetch('/api/sync-attendance', {
                                method: 'POST',
                                body: JSON.stringify(item),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                }
                            });

                            if (response.ok) {
                                item.synced = true;
                                const updateTx = db.transaction('attendance', 'readwrite');
                                updateTx.objectStore('attendance').put(item);
                            } else {
                                syncErrors++;
                            }
                        } catch (err) {
                            console.error('[Service Worker] Sync failed for record:', item.id, err);
                            syncErrors++;
                        }
                    }
                }

                if (syncErrors === 0) resolve();
                else reject('Some items failed to sync in background.');
            };

            getAllRequest.onerror = () => reject('Failed to read IDB');
        };

        request.onerror = (event) => reject('Failed to open IDB');
    });
}

async function syncFeeData() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('sms-db', 3);

        request.onsuccess = async (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('fees')) {
                return resolve();
            }

            const transaction = db.transaction('fees', 'readwrite');
            const store = transaction.objectStore('fees');
            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = async () => {
                const allFees = getAllRequest.result;
                let syncErrors = 0;

                for (const fee of allFees) {
                    if (fee.status === 'pending_sync' || fee.status === 'failed') {
                        if ((fee.retry_count || 0) >= 10) continue; // More retries for background

                        try {
                            const response = await fetch('/api/sync-fees', {
                                method: 'POST',
                                body: JSON.stringify(fee),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const updateTx = db.transaction('fees', 'readwrite');
                                updateTx.objectStore('fees').delete(fee.transaction_id);
                            } else {
                                fee.status = 'failed';
                                fee.retry_count = (fee.retry_count || 0) + 1;
                                const updateTx = db.transaction('fees', 'readwrite');
                                updateTx.objectStore('fees').put(fee);
                                syncErrors++;
                            }
                        } catch (err) {
                            console.error('[Service Worker] Fee sync failed for record:', fee.transaction_id, err);
                            fee.status = 'failed';
                            fee.retry_count = (fee.retry_count || 0) + 1;
                            const updateTx = db.transaction('fees', 'readwrite');
                            updateTx.objectStore('fees').put(fee);
                            syncErrors++;
                        }
                    }
                }

                self.clients.matchAll().then(clients => {
                    clients.forEach(client => {
                        client.postMessage({ type: syncErrors === 0 ? 'SYNC_SUCCESS' : 'SYNC_FAILED' });
                    });
                });

                if (syncErrors === 0) resolve();
                else reject('Some fees failed to sync in background.');
            };

            getAllRequest.onerror = () => reject('Failed to read IDB');
        };

        request.onerror = (event) => reject('Failed to open IDB');
    });
}
