<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Own Education</title>
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            @php
            $nameParts = explode(' ', $schoolName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            @endphp
            <!-- Logo -->
            <div class="flex items-center justify-center p-4 border-b border-slate-700/50 text-center">
                <div class="flex items-center gap-3 w-full justify-center">
                    <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="h-[40px] w-[40px] rounded-full object-cover bg-white ring-2 ring-blue-500/50">
                    <div class="flex flex-col justify-center text-left max-w-[150px]">
                        <h1 class="font-bold text-sm tracking-tight leading-sm truncate" title="{{ $schoolName }}">{{ $firstName }} <span class="text-blue-400">{{ $lastName }}</span></h1>
                        <p class="text-[9px] text-slate-400 uppercase tracking-wider font-medium leading-tight mt-0.5">Admin Portal</p>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-1">
                <a href="{{ route('school admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('school admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-all">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    Dashboard
                </a>

                <div class="px-6 mt-6 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">School Management</p>
                </div>

                <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.students') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-user-graduate w-5 group-hover:text-blue-400 transition-colors"></i>
                        Students
                    </div>
                    @if(isset($pendingStudentCount) && $pendingStudentCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingStudentCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.parents.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.parents.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-users-line w-5 group-hover:text-pink-400 transition-colors"></i>
                    Parents
                </a>

                <a href="{{ route('admin.families.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.families.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-family w-5 group-hover:text-rose-400 transition-colors"></i>
                    <span>Families</span>
                    <span class="ml-auto text-[10px] bg-emerald-500/20 text-emerald-400 font-bold px-1.5 py-0.5 rounded-full">New</span>
                </a>

                <a href="{{ route('admin.teachers') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.teachers') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-chalkboard-user w-5 group-hover:text-emerald-400 transition-colors"></i>
                    Teachers
                </a>


                <a href="{{ route('admin.teacher-attendance.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.teacher-attendance.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-user-check w-5 group-hover:text-teal-400 transition-colors"></i>
                    Attendance
                </a>

                <a href="{{ route('admin.accountants.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.accountants.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-user-tie w-5 group-hover:text-purple-400 transition-colors"></i>
                    Accountants
                </a>

                <a href="{{ route('admin.classes') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.classes') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-layer-group w-5 group-hover:text-orange-400 transition-colors"></i>
                    All Classes
                </a>

                <a href="{{ route('admin.timetable.create') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.timetable.create') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-calendar-days w-5 group-hover:text-yellow-400 transition-colors"></i>
                    Time Table
                </a>

                <div class="px-6 mt-8 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Exams</p>
                </div>

                <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.subjects.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-book-open w-5 group-hover:text-red-400 transition-colors"></i>
                    Subjects
                </a>

                <a href="{{ route('admin.exam-terms.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.exam-terms.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-calendar-check w-5 group-hover:text-indigo-400 transition-colors"></i>
                    Exam Terms
                </a>

                <a href="{{ route('admin.exam-schedules.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.exam-schedules.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-clipboard-list w-5 group-hover:text-indigo-400 transition-colors"></i>
                    Exam Scheduler
                </a>

                <a href="{{ route('admin.exams.submitted') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.exams.submitted') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-file-signature w-5 group-hover:text-indigo-400 transition-colors"></i>
                    Submitted Papers
                </a>

                <div class="px-6 mt-8 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Fee & Expenses</p>
                </div>

                <a href="{{ route('admin.fees.categories.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.fees.categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-tags w-5 group-hover:text-purple-400 transition-colors"></i>
                    Fee Categories
                </a>

                <a href="{{ route('admin.fees.structure.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.fees.structure.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-coins w-5 group-hover:text-yellow-400 transition-colors"></i>
                    Fee Structure
                </a>

                <a href="{{ route('admin.fees.create') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.fees.create') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-plus-circle w-5 group-hover:text-green-400 transition-colors"></i>
                    Create Fee
                </a>

                <a href="{{ route('admin.fees.collect.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.fees.collect.*') && !request()->routeIs('admin.fees.create') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-hand-holding-dollar w-5 group-hover:text-emerald-400 transition-colors"></i>
                    Fee Collection
                </a>

                <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition-colors group shadow-sm" title="View Student Profiles for History & Cards">
                    <i class="fa-solid fa-id-card-clip w-5 group-hover:text-indigo-400 transition-colors"></i>
                    Fee Cards & History
                </a>

                <a href="{{ route('admin.fees.reports.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.fees.reports.*') || request()->routeIs('admin.fees.reports') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-chart-line w-5 group-hover:text-cyan-400 transition-colors"></i>
                    Fee Reports
                </a>

                <a href="{{ route('admin.expenses.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.expenses.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-receipt w-5 group-hover:text-pink-400 transition-colors"></i>
                    Expenses
                </a>

                <div class="px-6 mt-8 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Transport</p>
                </div>

                <a href="{{ route('admin.transport.routes.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.transport.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-bus w-5 group-hover:text-amber-400 transition-colors"></i>
                    Transport Routes
                </a>

                <div class="px-6 mt-8 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports</p>
                </div>

                <a href="{{ route('admin.reports.comprehensive') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.reports.comprehensive') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-chart-pie w-5 group-hover:text-blue-400 transition-colors"></i>
                    Comprehensive Reports
                </a>

                <div class="px-6 mt-8 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Communication</p>
                </div>

                <a href="{{ route('admin.meetings.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl {{ request()->routeIs('admin.meetings.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-video w-5 group-hover:text-cyan-400 transition-colors"></i>
                    Staff Meetings
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition-colors group">
                    <i class="fa-solid fa-gear w-5 group-hover:text-orange-400 transition-colors"></i>
                    Settings
                </a>
            </nav>

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin" class="w-10 h-10 rounded-full object-cover border-2 border-slate-600">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 truncate">Super Admin</p>
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
                        <input type="text" placeholder="Search anything..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all placeholder-gray-400">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Go Home">
                        <i class="fa-solid fa-house text-xl"></i>
                    </a>

                    <div id="pwa-sync-status" class="hidden items-center transition-all"></div>

                    <a href="{{ route('admin.notifications.history') }}" class="relative p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors" title="Notification History">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </a>
                    <a href="{{ route('admin.notifications.create') }}" class="relative p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors" title="Send Notification">
                        <i class="fa-regular fa-paper-plane text-xl"></i>
                    </a>

                    <a href="{{ route('admin.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                            <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin" class="w-8 h-8 rounded-full object-cover">
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
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
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@owneducation.com' }}</p>
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
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
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

            // License Expiry Warning
            @if(isset($licenseDaysRemaining))
                Swal.fire({
                    icon: 'warning',
                    title: 'License Expiring Soon!',
                    text: "Your License Key was expire in {{ $licenseDaysRemaining }} {{ Str::plural('day', $licenseDaysRemaining) }}.",
                    confirmButtonText: 'Contact Support',
                    confirmButtonColor: '#3085d6',
                });
            @endif
        });
    </script>
    
    @include('partials.pwa-scripts')
</body>

</html>