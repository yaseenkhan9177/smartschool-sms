<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin | Own Education</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false, profileOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 sidebar-gradient text-white transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">

            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/img/logo-round.jpg') }}" alt="SaaS Master" class="h-[35px] w-[35px] rounded-full object-cover">
                    <div class="flex flex-col justify-center">
                        <h1 class="font-bold text-lg tracking-tight leading-none">SaaS <span class="text-indigo-400">Master</span></h1>
                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-medium leading-tight">Super Admin</p>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            @include('super_admin.sidebar')

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <img src="{{ asset('images/admin-avatar.png') }}" alt="Super Admin" class="w-10 h-10 rounded-full object-cover border-2 border-slate-600">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'super@admin.com' }}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50 relative">

            <!-- Top Header -->
            <header class="h-20 glass-effect border-b border-gray-200 flex items-center justify-between px-6 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Search -->
                    <div class="hidden md:flex items-center relative w-96">
                        <i class="fa-solid fa-search absolute left-3 text-gray-400"></i>
                        <input type="text" placeholder="Search schools, licenses..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all placeholder-gray-400">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Go Home">
                        <i class="fa-solid fa-house text-xl"></i>
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                            <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin" class="w-8 h-8 rounded-full object-cover">
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Super Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-50">
                                <p class="text-sm font-medium text-gray-900">Signed in as</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'super@admin.com' }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                            <div class="border-t border-gray-50 mt-1"></div>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign out</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                @yield('content')

                <footer class="mt-12 text-center text-xs text-gray-400 pb-8">
                    &copy; {{ date('Y') }} SaaS Master. All rights reserved.
                </footer>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>

</html>