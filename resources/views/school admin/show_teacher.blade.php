<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile | AstriaLearning</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
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

    <style>
        [x-cloak] { display: none !important; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Reused) -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 sidebar-gradient text-white transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-graduation-cap text-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl tracking-tight">Astria<span class="text-blue-400">Learning</span></h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Admin Portal</p>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
                
                <a href="{{ route('school admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-chart-pie w-5 group-hover:text-blue-400 transition-colors"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-user-graduate w-5 group-hover:text-emerald-400 transition-colors"></i>
                    Students
                </a>
                
                <a href="{{ route('admin.teachers') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 transition-all">
                    <i class="fa-solid fa-chalkboard-user w-5"></i>
                    Teachers
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
                    <h2 class="text-xl font-bold text-gray-800">Teacher Profile</h2>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                
                <div class="max-w-5xl mx-auto">
                    <!-- Back Button -->
                    <a href="{{ route('admin.teachers') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 mb-6 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Teachers List
                    </a>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Teacher Info Card -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 flex flex-col items-center text-center">
                                    <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-br from-blue-500 to-indigo-600 mb-4">
                                        <img src="{{ asset('uploads/'.$teacher->image) }}" alt="{{ $teacher->name }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $teacher->name }}</h3>
                                    <p class="text-sm text-blue-600 font-medium mb-1">{{ $teacher->education_level }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->subject }} Department</p>
                                    
                                    <div class="w-full mt-6 pt-6 border-t border-gray-100 space-y-3">
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                                <i class="fa-regular fa-envelope"></i>
                                            </div>
                                            <span class="truncate">{{ $teacher->email }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                                <i class="fa-solid fa-book"></i>
                                            </div>
                                            <span>{{ $teacher->subject }}</span>
                                        </div>
                                    </div>

                                    <div class="w-full mt-6 pt-6 border-t border-gray-100">
                                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Classes Section -->
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fa-solid fa-chalkboard-user text-blue-600"></i>
                                        Assigned Classes
                                    </h3>
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">
                                        {{ $teacher->schoolClasses->count() }} Classes
                                    </span>
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap gap-3">
                                        @forelse($teacher->schoolClasses as $class)
                                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-blue-600 shadow-sm">
                                                    <i class="fa-solid fa-users text-xs"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">{{ $class->name }}</span>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 italic">No classes assigned to this teacher yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Timetable Section -->
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar-days text-blue-600"></i>
                                        Class Schedule
                                    </h3>
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">
                                        {{ $timetables->count() }} Classes Assigned
                                    </span>
                                </div>
                                
                                <div class="overflow-x-auto">
                                    @if($timetables->count() > 0)
                                    <table class="w-full text-left text-sm text-gray-600">
                                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                                            <tr>
                                                <th class="px-6 py-4">Day</th>
                                                <th class="px-6 py-4">Time</th>
                                                <th class="px-6 py-4">Class</th>
                                                <th class="px-6 py-4">Subject</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($timetables as $timetable)
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{ ucfirst($timetable->day) }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-regular fa-clock text-gray-400 text-xs"></i>
                                                        {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }} - 
                                                        {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                        {{ $timetable->schoolClass->name }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-900">
                                                    {{ $timetable->subject->name }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <div class="p-12 text-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                                            <i class="fa-regular fa-calendar-xmark"></i>
                                        </div>
                                        <h4 class="text-gray-900 font-medium mb-1">No Classes Assigned</h4>
                                        <p class="text-gray-500 text-sm">This teacher has not been assigned any classes in the timetable yet.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="mt-12 text-center text-xs text-gray-400 pb-8">
                    &copy; {{ date('Y') }} AstriaLearning Systems. All rights reserved.
                </footer>

            </main>
        </div>
    </div>
</body>
</html>
