<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || localStorage.getItem('darkMode') === null, sidebarOpen: false }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | SMS Portal</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Light Mode Background */
        /* Light Mode Background */
        body {
            /* Cleaner, more professional slate gradient */
            background: linear-gradient(to bottom right, #f8fafc, #f1f5f9, #e2e8f0);
            /* Fallback color */
            background-color: #f8fafc;
        }

        /* Dark Mode Background */
        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }

        /* Glass Effect - Light Mode */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        /* Glass Effect - Dark Mode */
        .dark .glass {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        /* Glass Card - Light Mode */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        /* Glass Card - Dark Mode */
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.08);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }

        /* Sidebar Animation */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="min-h-screen transition-colors duration-300">
    <!-- Sidebar Overlay -->
    <!-- Sidebar Overlay (Mobile Only) -->
    <div x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-50 h-full w-72 glass shadow-2xl transition-transform duration-300 transform lg:translate-x-0 lg:block">

        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            @php
            $nameParts = explode(' ', $schoolName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            @endphp
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 w-full">
                    @if($schoolLogo)
                    <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="h-[40px] w-[40px] rounded-full object-cover bg-white ring-2 ring-blue-500/50">
                    @else
                    <div class="shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    @endif
                    <div class="flex flex-col justify-center text-left max-w-[150px]">
                        <h2 class="font-bold text-sm tracking-tight leading-tight text-gray-900 dark:text-white truncate" title="{{ $schoolName }}">{{ $firstName }} <span class="text-blue-500">{{ $lastName }}</span></h2>
                        <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mt-0.5">Student Portal</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors hidden lg:block">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('student.schedule') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Class Schedule</span>
                </a>

                <a href="{{ route('student.calendar.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <i class="fas fa-calendar-alt w-5 text-center text-lg"></i>
                    <span class="font-medium">Personal Calendar</span>
                </a>

                <a href="{{ route('student.exams.admit-card') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <i class="fas fa-calendar-check w-5 text-center text-lg"></i>
                    <span class="font-medium">Exam Schedule</span>
                </a>

                <a href="{{ route('student.assignments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="font-medium">Assignments</span>
                </a>

                <a href="{{ route('student.homework.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <i class="fa-solid fa-book-open w-5 text-center text-lg"></i>
                    <span class="font-medium">Homework</span>
                </a>

                <a href="{{ route('student.fees.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">My Fees</span>
                </a>

                <a href="{{ route('student.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>

                <a href="{{ route('student.notifications.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all group relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="font-medium">Notifications</span>
                    @if($currentUser && $currentUser->unreadNotifications()->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $currentUser->unreadNotifications()->count() }}</span>
                    @endif
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('logout') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-all group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <div class="flex flex-col min-h-screen lg:ml-72 transition-all duration-300">
        <!-- Header -->
        <header class="glass sticky top-0 z-30 transition-all duration-300">
            <div class="container mx-auto px-4 sm:px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <!-- Menu Button (Mobile/Tablet) -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-blue-50 dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-6 h-6 text-blue-700 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">S</span>
                        </div>
                        <h1 class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-400 dark:to-blue-600 bg-clip-text text-transparent">
                            Student Portal
                        </h1>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <!-- Theme Toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 sm:p-2.5 rounded-xl bg-blue-50 dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-slate-700 transition-all duration-300">
                            <svg x-show="!darkMode" class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        <a href="{{ url('/') }}" class="hidden sm:flex glass px-3 py-2 rounded-full hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors text-gray-700 dark:text-gray-300" title="Go Home">
                            <span class="text-xl">🏠</span>
                        </a>

                        <a href="{{ route('student.notifications.index') }}" class="glass px-3 py-2 rounded-full hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors text-gray-700 dark:text-gray-300 relative" title="Notifications">
                            <span class="text-xl">🔔</span>
                            @if($currentUser && $currentUser->unreadNotifications()->count() > 0)
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                            @endif
                        </a>

                        <div class="hidden sm:flex glass px-3 sm:px-4 py-2 rounded-full items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <!-- Page Header -->
            <div class="mb-6 sm:mb-8">
                <!-- Session Alerts -->
                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm flex items-center justify-between animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm flex items-center justify-between animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    @yield('header', 'Welcome back, ' . (session('student_name') ?? 'Student') . '! 👋')
                </h1>
            </div>

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="glass mt-auto">
            <div class="container mx-auto px-4 sm:px-6 py-4">
                <div class="text-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    © {{ date('Y') }} School Management System. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
    
    @include('partials.pwa-scripts')
</body>

</html>