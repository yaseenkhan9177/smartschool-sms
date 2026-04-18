<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || localStorage.getItem('darkMode') === null, sidebarOpen: false }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | SMS Portal</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Light/Dark Backgrounds */
        body {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        }

        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }

        /* Glass Effects */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139, 92, 246, 0.1);
        }

        .dark .glass {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="min-h-screen transition-colors duration-300">
    <!-- Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-50 h-full w-72 glass shadow-2xl transition-transform duration-300 transform lg:block">

        <div class="flex flex-col h-full">
            @php
            $nameParts = explode(' ', $schoolName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            @endphp
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 w-full">
                    @if($schoolLogo)
                    <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="h-[40px] w-[40px] rounded-full object-cover bg-white ring-2 ring-indigo-500/50">
                    @else
                    <div class="shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">T</span>
                    </div>
                    @endif
                    <div class="flex flex-col justify-center text-left max-w-[150px]">
                        <h2 class="font-bold text-sm tracking-tight leading-tight text-gray-900 dark:text-white truncate" title="{{ $schoolName }}">{{ $firstName }} <span class="text-indigo-500">{{ $lastName }}</span></h2>
                        <p class="text-[9px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mt-0.5">Teacher Portal</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="fas fa-times text-gray-600 dark:text-gray-400"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('teacher.my_classes') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.my_classes') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-chalkboard-teacher w-5"></i>
                    <span class="font-medium">My Classes</span>
                </a>

                <a href="{{ route('teacher.assignments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.assignments.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-book w-5"></i>
                    <span class="font-medium">Assignments</span>
                </a>

                <a href="{{ route('teacher.exams.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.exams.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-file-upload w-5"></i>
                    <span class="font-medium">Exams</span>
                </a>

                <a href="{{ route('teacher.marks.create') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.marks.*') ? 'bg-purple-50 text-purple-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-marker mr-3 text-lg {{ request()->routeIs('teacher.marks.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Marks Entry
                </a>

                <a href="{{ route('teacher.online-classes.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.online-classes.*') ? 'bg-purple-50 text-purple-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-video mr-3 text-lg {{ request()->routeIs('teacher.online-classes.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Online Classes
                </a>


                <a href="{{ route('teacher.homework.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.homework.*') ? 'bg-purple-50 text-purple-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-book-open mr-3 text-lg {{ request()->routeIs('teacher.homework.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Homework
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Communication</p>
                </div>

                <a href="{{ route('teacher.meetings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.meetings.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-video w-5"></i>
                    <span class="font-medium">Staff Meetings</span>
                </a>

                <a href="{{ route('teacher.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group {{ request()->routeIs('teacher.profile') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-user-circle w-5"></i>
                    <span class="font-medium">Profile</span>
                </a>

                <a href="{{ route('teacher.notifications.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group relative">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">Notifications</span>
                    @if($currentUser && $currentUser->unreadNotifications()->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $currentUser->unreadNotifications()->count() }}</span>
                    @endif
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('logout') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-all group">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <div class="flex flex-col min-h-screen lg:ml-72 transition-all duration-300">
        <!-- Header -->
        <header class="glass sticky top-0 z-30">
            <div class="container mx-auto px-4 sm:px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                            @yield('header', 'Overview')
                        </h1>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button @click="darkMode = !darkMode" class="p-2 rounded-xl bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-slate-700 transition-colors">
                            <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                        </button>
                        <a href="{{ url('/') }}" class="hidden sm:flex glass px-3 py-2 rounded-full hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors text-gray-700 dark:text-gray-300" title="Go Home">
                            <span class="text-xl">🏠</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 container mx-auto px-4 sm:px-6 py-8">
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    
    @include('partials.pwa-scripts')
</body>

</html>