@extends('layouts.teacher')

@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Classes -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-chalkboard text-xl"></i>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">My Classes</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $teacher->schoolClasses->count() }}</h3>
            </div>
        </div>

        <!-- Total Assignments -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">Assignments</p>
                <!-- Assuming we might want to count actual assignments later, for now using mock or if relation exists -->
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $assignmentsCount }}</h3>
            </div>
        </div>

        <!-- Pending Grading -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-yellow-500/10 text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">Pending Grading</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $pendingGradingCount }}</h3>
            </div>
        </div>

        <!-- Average Attendance -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-green-500/10 text-green-600 dark:text-green-400">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">Avg Attendance</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $averageAttendance }}%</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- My Classes / Attendance Shortcuts -->
        <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-chalkboard-user mr-2 text-indigo-500"></i>
                    My Classes & Attendance
                </h3>
                <a href="{{ route('teacher.my_classes') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All Classes &rarr;</a>
            </div>

            <div class="p-6 grid grid-cols-1 gap-4">
                @forelse($teacher->schoolClasses as $class)
                <div class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-md transition-all bg-white dark:bg-gray-800/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                            {{ substr($class->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $class->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $class->section ?? 'Section A' }} • 30 Students</p>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.attendance', $class->id) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 dark:shadow-none transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-check-double"></i>
                            <span class="hidden sm:inline font-medium">Take Attendance</span>
                        </a>
                        <a href="{{ route('teacher.class.show', $class->id) }}" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="View Details">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <i class="fas fa-chalkboard text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No classes assigned yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Staff Meetings -->
        @if(isset($upcomingMeetings) && $upcomingMeetings->count() > 0)
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col mb-6">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Staff Meetings</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($upcomingMeetings as $meeting)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 flex items-center justify-center">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $meeting->topic }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $meeting->start_time->format('d M, h:i A') }}
                                    @if($meeting->status == 'started')
                                    <span class="text-green-600 font-bold ml-1">(Live)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('meetings.join', $meeting->id) }}" target="_blank" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">Join</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Events -->
        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col mb-6">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Upcoming Events</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex flex-col items-center justify-center border border-indigo-100 dark:border-indigo-800">
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase">{{ $event->event_date->format('M') }}</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $event->event_date->format('d') }}</span>
                        </div>
                        <div>
                            <h4 class="text-gray-900 dark:text-white font-semibold">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ $event->description }}</p>
                            <span class="text-xs text-gray-400 mt-1 block">{{ $event->event_date->format('h:i A') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Online Classes -->
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col mb-6">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Live Classes</h3>
                <a href="{{ route('teacher.online-classes.create') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">+ New</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($onlineClasses as $class)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-800">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-800 text-purple-600 dark:text-purple-300 flex items-center justify-center">
                                <i class="fa-solid fa-video"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $class->topic }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class->schoolClass->name }} • {{ $class->start_time->format('h:i A') }}</p>
                            </div>
                        </div>
                        <a href="javascript:void(0)" onclick="confirmStart('{{ $class->start_url }}')" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">Start</a>
                    </div>
                    @empty
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">No classes scheduled.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col h-full">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Recent Activity</h3>
            </div>
            <div class="p-6 flex-1 overflow-y-auto max-h-[400px]">
                <div class="space-y-6">
                    @forelse($recentActivities as $activity)
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0 mt-1">
                            @php
                            $colorClass = match($activity->action_type) {
                            'assignment' => 'bg-green-500 ring-green-100 dark:ring-green-900/30',
                            'attendance' => 'bg-blue-500 ring-blue-100 dark:ring-blue-900/30',
                            'online_class' => 'bg-purple-500 ring-purple-100 dark:ring-purple-900/30',
                            default => 'bg-gray-500 ring-gray-100 dark:ring-gray-900/30'
                            };
                            @endphp
                            <div class="w-2 h-2 rounded-full {{ $colorClass }} ring-4"></div>
                        </div>
                        <div>
                            <p class="text-gray-800 dark:text-gray-200 font-medium text-sm">{{ $activity->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 text-sm">No recent activity.</p>
                    @endforelse
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-center">
                <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">View All Activity</a>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function confirmStart(url) {
        Swal.fire({
            title: 'Start Class?',
            text: "Are you sure you want to start this online class now?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, start it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(url, '_blank');
            }
        })
    }
</script>
@endsection