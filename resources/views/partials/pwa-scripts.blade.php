<!-- idb library for easier IndexedDB usage -->
<script src="https://cdn.jsdelivr.net/npm/idb@8/build/umd.js"></script>

<!-- UI Element for Offline Status -->
<div id="pwa-offline-indicator" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: #ef4444; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: 500; align-items: center; gap: 8px;">
    <i class="fa-solid fa-wifi-slash"></i> Offline Mode - Data will sync when connected
</div>

<script>
    // 1. Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    
                    // Register Background Sync for fees
                    if ('sync' in registration) {
                        navigator.serviceWorker.ready.then(sw => {
                            return sw.sync.register('sync-fees');
                        }).catch(() => {
                            console.log("Background sync registration failed (likely unsupported or permission denied)");
                        });
                    }
                })
                .catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });

            // Listen for SW messages
            navigator.serviceWorker.addEventListener('message', event => {
                if (event.data.type === 'SYNC_SUCCESS') {
                    localStorage.setItem('last_sync_time', new Date().toISOString());
                    if (typeof updateSyncUI === 'function') updateSyncUI();
                } else if (event.data.type === 'SYNC_FAILED') {
                    if (typeof updateSyncUI === 'function') updateSyncUI();
                }
            });
        });
    }

    // 2. Setup IndexedDB using the idb library
    let smsDbPromise;
    if (window.idb) {
        smsDbPromise = idb.openDB('sms-db', 3, {
            upgrade(db, oldVersion, newVersion, transaction) {
                if (!db.objectStoreNames.contains('attendance')) {
                    db.createObjectStore('attendance', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('fees')) {
                    db.createObjectStore('fees', { keyPath: 'transaction_id' });
                }
            }
        });
    } else {
        console.warn("idb library not loaded. Offline storage won't work correctly.");
    }

    // 3. Online/Offline Detection
    const offlineIndicator = document.getElementById('pwa-offline-indicator');

    function updateOnlineStatus() {
        if (!navigator.onLine) {
            offlineIndicator.style.display = 'flex';
            console.log("App is Offline");
        } else {
            offlineIndicator.style.display = 'none';
            console.log("App is Online");
            // Trigger manual sync fallback in case Background Sync API fails or isn't supported
            triggerManualSync();
        }
    }

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    // Initial check
    updateOnlineStatus();

    // 4. Manual Sync (Fallback for browsers not supporting Background Sync)
    async function triggerManualSync() {
        if (!smsDbPromise) return;

        const db = await smsDbPromise;
        let totalSynced = 0;
        
        // --- Sync Attendance ---
        if (db.objectStoreNames.contains('attendance')) {
            const allAttendance = await db.getAll('attendance');
            for (const item of allAttendance) {
                if (!item.synced) {
                    try {
                        const response = await fetch('/api/sync-attendance', {
                            method: 'POST',
                            body: JSON.stringify(item),
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            item.synced = true;
                            await db.put('attendance', item);
                            totalSynced++;
                        }
                    } catch (e) {
                         console.error("Failed to manual sync attendance", item.id);
                    }
                }
            }
        }

        // --- Sync Fees ---
        if (db.objectStoreNames.contains('fees')) {
            const allFees = await db.getAll('fees');
            for (const fee of allFees) {
                if (fee.status === 'pending_sync' || fee.status === 'failed') {
                    // Safety check: Don't retry more than 5 times
                    if ((fee.retry_count || 0) >= 5) continue;

                    try {
                        const response = await fetch('/api/sync-fees', {
                            method: 'POST',
                            body: JSON.stringify(fee),
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            await db.delete('fees', fee.transaction_id);
                            totalSynced++;
                        } else {
                            fee.status = 'failed';
                            fee.retry_count = (fee.retry_count || 0) + 1;
                            await db.put('fees', fee);
                        }
                    } catch (e) {
                         console.error("Failed to manual sync fee", fee.transaction_id);
                         fee.status = 'failed';
                         fee.retry_count = (fee.retry_count || 0) + 1;
                         await db.put('fees', fee);
                    }
                }
            }
        }

        if (totalSynced > 0) {
            localStorage.setItem('last_sync_time', new Date().toISOString());
            if (typeof updateSyncUI === 'function') updateSyncUI();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `Synced ${totalSynced} offline records!`,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    }

    // --- UI Update & Utility Logic ---
    function timeAgo(dateString) {
        if (!dateString) return 'never';
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 3153600;
        if (interval > 1) return Math.floor(interval) + " months ago";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hr ago";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " min ago";
        return "just now";
    }

    async function updateSyncUI() {
        const syncContainer = document.getElementById('pwa-sync-status');
        if (!syncContainer || !window.smsDbPromise) return;
        
        try {
            const db = await window.smsDbPromise;
            if (!db.objectStoreNames.contains('fees')) return;
            
            const allFees = await db.getAll('fees');
            const pending = allFees.filter(f => f.status === 'pending_sync');
            const failed = allFees.filter(f => f.status === 'failed');
            const lastSyncStr = localStorage.getItem('last_sync_time');
            
            syncContainer.classList.remove('hidden');
            
            if (failed.length > 0) {
                syncContainer.innerHTML = `
                    <div class="flex items-center gap-2 bg-red-100 text-red-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-red-200">
                        <i class="fa-solid fa-circle-exclamation"></i> Sync Failed (${failed.length})
                        <button onclick="triggerManualSync()" class="ml-2 bg-red-600 text-white px-2 py-0.5 rounded hover:bg-red-700 transition">Retry Now</button>
                    </div>
                `;
            } else if (pending.length > 0) {
                syncContainer.innerHTML = `
                    <div class="flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-yellow-200">
                        <i class="fa-solid fa-rotate fa-spin"></i> ${pending.length} records pending
                    </div>
                `;
            } else {
                syncContainer.innerHTML = `
                    <div class="flex items-center gap-2 text-green-600 px-3 py-1.5 text-xs font-medium bg-green-50 rounded-xl border border-green-100">
                        <i class="fa-solid fa-cloud-check"></i> Synced: ${timeAgo(lastSyncStr)}
                    </div>
                `;
            }
        } catch (e) {
            console.error("Error updating Sync UI", e);
        }
    }

    // Track online/offline status
    window.addEventListener('online', () => { triggerManualSync(); updateSyncUI(); });
    window.addEventListener('offline', updateSyncUI);

    setInterval(updateSyncUI, 10000);
    window.addEventListener('load', () => setTimeout(updateSyncUI, 1000));

    // Expose generated Hash utility for validation
    window.generateValidationHash = async function(str) {
        const msgBuffer = new TextEncoder().encode(str);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    };
</script>
