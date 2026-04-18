<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard | Own Education</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
        }

        .nav-item-active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left: 4px solid #a855f7;
        }

        .nav-item-inactive {
            color: #94a3b8;
            border-left: 4px solid transparent;
        }

        .nav-item-inactive:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 sidebar-gradient text-white transition-transform duration-300 ease-in-out transform lg:static lg:translate-x-0 flex flex-col"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">

            @php
            $nameParts = explode(' ', $schoolName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            @endphp
            <!-- Logo -->
            <div class="flex items-center justify-center p-4 border-b border-white/10 text-center">
                <div class="flex items-center gap-3 w-full justify-center">
                    @if($schoolLogo)
                    <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="h-[40px] w-[40px] rounded-full object-cover bg-white ring-2 ring-purple-500/50">
                    @else
                    <div class="shrink-0 w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center shadow-lg shadow-purple-600/30">
                        <i class="fa-solid fa-calculator text-xl text-white"></i>
                    </div>
                    @endif
                    <div class="flex flex-col justify-center text-left max-w-[150px]">
                        <h1 class="font-bold text-sm tracking-tight leading-tight truncate" title="{{ $schoolName }}">{{ $firstName }} <span class="text-purple-400">{{ $lastName }}</span></h1>
                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-medium mt-0.5">Accountant Portal</p>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-1">
                <p class="px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>

                <a href="{{ route('accountant.dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.dashboard') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    Dashboard
                </a>

                <a href="{{ route('accountant.students.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.students.index') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-users w-5"></i>
                    Students
                </a>

                <a href="{{ route('accountant.parents.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.parents.index') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-users-line w-5"></i>
                    Parents
                </a>

                <a href="{{ route('accountant.families.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.families.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-people-roof w-5"></i>
                    <span>Families</span>
                    <span class="ml-auto text-[10px] bg-purple-500/30 text-purple-300 font-bold px-1.5 py-0.5 rounded-full">New</span>
                </a>

                <a href="{{ route('accountant.teacher-attendance.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.teacher-attendance.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-user-check w-5"></i>
                    Teacher Attendance
                </a>


                <p class="px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Fee Management</p>

                <a href="{{ route('accountant.fees.categories.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.fees.categories.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-tags w-5"></i>
                    Fee Categories
                </a>

                <a href="{{ route('accountant.fees.structure.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.fees.structure.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    Fee Structure
                </a>

                <a href="{{ route('accountant.fees.create') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.fees.create') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-plus-circle w-5"></i>
                    Create Fee
                </a>

                <a href="{{ route('accountant.fees.collect.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.fees.collect.*') && !request()->routeIs('accountant.fees.create') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-hand-holding-dollar w-5"></i>
                    Fee Collection
                </a>

                <a href="{{ route('accountant.students.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all text-slate-400 hover:bg-white/5 hover:text-white" title="View Student Profiles for History & Cards">
                    <i class="fa-solid fa-id-card-clip w-5"></i>
                    Fee Cards & History
                </a>

                <a href="{{ route('accountant.fees.reports.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.fees.reports.*') || request()->routeIs('accountant.fees.reports') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    Fee Reports
                </a>

                <p class="px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Expenses</p>

                <a href="{{ route('accountant.expenses.categories.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.expenses.categories.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-list w-5"></i>
                    Expense Categories
                </a>

                <a href="{{ route('accountant.expenses.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.expenses.index') || request()->routeIs('accountant.expenses.create') || request()->routeIs('accountant.expenses.edit') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-receipt w-5"></i>
                    Expenses
                </a>
                <p class="px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Exams</p>

                <a href="{{ route('accountant.exams.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.exams.index') || request()->routeIs('accountant.exams.show_class') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-id-card w-5"></i>
                    Admit Cards
                </a>

                <a href="{{ route('accountant.results.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.results.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-file-invoice w-5"></i>
                    Student DMCs
                </a>

                <a href="{{ route('accountant.certificates.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.certificates.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-certificate w-5"></i>
                    Student Certificates
                </a>

                <a href="{{ route('accountant.exams.submitted') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.exams.submitted') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-file-signature w-5"></i>
                    Submitted Papers
                </a>

                <p class="px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Communication</p>

                <a href="{{ route('accountant.meetings.index') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all {{ request()->routeIs('accountant.meetings.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <i class="fa-solid fa-video w-5"></i>
                    Staff Meetings
                </a>
            </nav>

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Accountant" class="w-10 h-10 rounded-full object-cover border-2 border-purple-500">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Accountant' }}</p>
                        <p class="text-xs text-slate-400 truncate">Finance Dept</p>
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
            <header class="h-20 glass-effect border-b border-gray-200 flex items-center justify-between px-6 z-30 sticky top-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Search -->
                    <div class="hidden md:flex items-center relative w-96">
                        <i class="fa-solid fa-search absolute left-3 text-gray-400"></i>
                        <input type="text" placeholder="Search invoices, students..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all placeholder-gray-400">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors" title="Go Home">
                        <i class="fa-solid fa-house text-xl"></i>
                    </a>

                    <div id="pwa-sync-status" class="hidden items-center transition-all"></div>

                    <a href="{{ route('accountant.notifications.create') }}" class="relative p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors" title="Send Notification">
                        <i class="fa-regular fa-paper-plane text-xl"></i>
                    </a>
                    <a href="{{ route('accountant.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @if($currentUser && $currentUser->unreadNotifications()->count() > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Accountant" class="w-8 h-8 rounded-full object-cover">
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Accountant' }}</span>
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
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'accountant@owneducation.com' }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Your Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                            <div class="border-t border-gray-50 mt-1"></div>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign out</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6 lg:p-8">
                @yield('content')

                <footer class="mt-12 text-center text-xs text-gray-400 pb-8">
                    &copy; {{ date('Y') }} Own Education Systems. All rights reserved.
                </footer>
            </main>
        </div>
    </div>

    @yield('scripts')

    <!-- Flash Messages via SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            const flashes = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info'))
            };

            for (const [type, message] of Object.entries(flashes)) {
                if (message) {
                    Toast.fire({
                        icon: type,
                        title: message
                    });
                }
            }
        });
    </script>
    
    @include('partials.pwa-scripts')
</body>

</html>