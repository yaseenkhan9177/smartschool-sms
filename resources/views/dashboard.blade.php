@extends('layouts.student')

@section('header')
Welcome back, <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-400 to-orange-400">{{ session('student_name') ?? 'Student' }}</span>! 👋
@endsection

@section('content')
<style>
    /* 3D Card Animation Styles */
    .card-3d {
        transform-style: preserve-3d;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .card-3d:hover {
        transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
    }

    .card-3d-content {
        transform: translateZ(50px);
    }

    /* Parallax Container */
    .parallax-container {
        perspective: 1000px;
    }

    /* Individual card tilt on mouse move */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .card-float {
        animation: float 6s ease-in-out infinite;
    }

    .card-float:nth-child(2) {
        animation-delay: 0.5s;
    }

    .card-float:nth-child(3) {
        animation-delay: 1s;
    }

    .card-float:nth-child(4) {
        animation-delay: 1.5s;
    }
</style>

<!-- Stats Grid with Parallax -->
<div class="parallax-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 animate-fade-in" style="animation-delay: 0.1s;"
    x-data="{ 
             cards: [
                 { rotateX: 0, rotateY: 0 },
                 { rotateX: 0, rotateY: 0 },
                 { rotateX: 0, rotateY: 0 },
                 { rotateX: 0, rotateY: 0 }
             ]
         }"
    @mousemove.window="
             $el.querySelectorAll('.card-3d').forEach((card, index) => {
                 const rect = card.getBoundingClientRect();
                 const x = $event.clientX - rect.left;
                 const y = $event.clientY - rect.top;
                 const centerX = rect.width / 2;
                 const centerY = rect.height / 2;
                 const rotateX = (y - centerY) / 10;
                 const rotateY = (centerX - x) / 10;
                 cards[index].rotateX = rotateX;
                 cards[index].rotateY = rotateY;
                 card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
             });
         "
    @mouseleave.window="
             $el.querySelectorAll('.card-3d').forEach((card, index) => {
                 card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
             });
         ">

    <!-- Stat Card 1: Grade Average (New) -->
    <div class="card-3d card-float glass-card p-6 rounded-2xl hover:bg-white/50 dark:hover:bg-white/5 transition-all duration-300 group cursor-pointer relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="card-3d-content relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-xl bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                @if($averagePercentage >= 80)
                <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg border border-green-200 dark:border-green-500/30">Excellent</span>
                @elseif($averagePercentage >= 60)
                <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-2 py-1 rounded-lg border border-yellow-200 dark:border-yellow-500/30">Good</span>
                @else
                <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-lg border border-red-200 dark:border-red-500/30">Low</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-1 group-hover:scale-110 transition-transform">{{ $averagePercentage }}%</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Grade Average</p>
        </div>
    </div>

    <!-- Stat Card 2: Assignments Due -->
    <a href="{{ route('student.assignments.index') }}" class="card-3d card-float glass-card p-6 rounded-2xl hover:bg-white/50 dark:hover:bg-white/5 transition-all duration-300 group cursor-pointer relative overflow-hidden block">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="card-3d-content relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-xl bg-purple-500/20 text-purple-600 dark:text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                @if($assignmentsDueCount > 0)
                <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-lg border border-red-200 dark:border-red-500/30 animate-pulse">Urgent</span>
                @else
                <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg border border-green-200 dark:border-green-500/30">Clear</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-1 group-hover:scale-110 transition-transform">{{ $assignmentsDueCount }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Assignments Due</p>
        </div>
    </a>

    <!-- Stat Card 3: Attendance Rate -->
    <div class="card-3d card-float glass-card p-6 rounded-2xl hover:bg-white/50 dark:hover:bg-white/5 transition-all duration-300 group cursor-pointer relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="card-3d-content relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-xl bg-green-500/20 text-green-600 dark:text-green-400 group-hover:bg-green-500 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
                @if($attendanceRate >= 90)
                <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg border border-green-200 dark:border-green-500/30">Excellent</span>
                @elseif($attendanceRate >= 75)
                <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-2 py-1 rounded-lg border border-yellow-200 dark:border-yellow-500/30">Good</span>
                @else
                <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-lg border border-red-200 dark:border-red-500/30">Low</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-1 group-hover:scale-110 transition-transform">{{ $attendanceRate }}%</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Attendance Rate</p>
        </div>
    </div>

    <!-- Stat Card 4: Fees Due (New) -->
    <a href="{{ route('student.fees.index') }}" class="card-3d card-float glass-card p-6 rounded-2xl hover:bg-white/50 dark:hover:bg-white/5 transition-all duration-300 group cursor-pointer relative overflow-hidden block">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="card-3d-content relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-xl bg-red-500/20 text-red-600 dark:text-red-400 group-hover:bg-red-500 group-hover:text-white transition-all duration-300 group-hover:scale-110 group-hover:rotate-12">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                @if($feesDue > 0)
                <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-lg border border-red-200 dark:border-red-500/30">Pending</span>
                @else
                <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg border border-green-200 dark:border-green-500/30">Paid</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-1 group-hover:scale-110 transition-transform">PKR {{ number_format($feesDue) }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Fees Due</p>
        </div>
    </a>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in" style="animation-delay: 0.2s;">

    <!-- Left Column: Next Class & Announcements -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Next Class Widget -->
        <div class="glass-card rounded-3xl p-8 relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-600/20 to-transparent rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h3 class="text-lg font-semibold text-red-400 mb-2">Up Next</h3>
                    <h2 class="text-3xl font-bold text-white mb-2">Advanced Mathematics</h2>
                    <div class="flex items-center text-gray-400 space-x-4">
                        <span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> 10:00 AM - 11:30 AM</span>
                        <span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg> Room 301</span>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="{{ route('student.schedule') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                        View Full Schedule <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming Assignments Widget -->
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">Upcoming Assignments</h2>
                <a href="{{ route('student.assignments.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($upcomingAssignments as $assignment)
                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="block glass-card p-4 rounded-xl hover:bg-white/5 transition-colors group border border-gray-700/50 hover:border-indigo-500/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="inline-block px-2 py-1 rounded-md text-xs font-medium bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-2">
                                {{ $assignment->subject->name }}
                            </span>
                            <h4 class="text-lg font-semibold text-white mb-1 group-hover:text-indigo-400 transition-colors">{{ $assignment->title }}</h4>
                            <div class="flex items-center text-gray-400 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Due: {{ $assignment->due_date->format('M d, H:i') }}
                            </div>
                        </div>
                        <div class="p-2 rounded-lg bg-gray-800 text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-6">
                    <p class="text-gray-500">No upcoming assignments.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Online Classes Widget -->
    <div class="glass-card p-6 rounded-3xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Live Classes</h2>
            <a href="#" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">View All</a>
        </div>
        <div class="space-y-4">
            @forelse($onlineClasses as $class)
            <div class="glass-card p-4 rounded-xl border border-gray-700/50 hover:border-purple-500/50 flex items-center justify-between group transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center border border-purple-500/30">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-white group-hover:text-purple-400 transition-colors">{{ $class->topic }}</h4>
                        <p class="text-gray-400 text-sm">{{ $class->subject->name }} • {{ $class->teacher->name }}</p>
                        <span class="text-xs text-gray-500 block mt-1"><i class="fa-regular fa-clock mr-1"></i> {{ $class->start_time->format('h:i A') }}</span>
                    </div>
                </div>
                <div>
                    @php
                    // Early Join Logic
                    $startTime = \Carbon\Carbon::parse($class->start_time);
                    $endTime = $startTime->copy()->addMinutes($class->duration);
                    $canJoin = now()->greaterThanOrEqualTo($startTime->subMinutes(10)) && now()->lessThanOrEqualTo($endTime);
                    $isEnded = now()->greaterThan($endTime);
                    @endphp

                    @if($canJoin)
                    <a href="{{ $class->join_url }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition-colors shadow-lg shadow-green-600/30 animate-pulse">Join Now</a>
                    @elseif($isEnded)
                    <span class="px-3 py-1 bg-gray-700 text-gray-400 rounded-lg text-xs font-bold">Ended</span>
                    @else
                    <span class="px-3 py-1 bg-blue-900/50 text-blue-400 border border-blue-500/30 rounded-lg text-xs font-bold">Upcoming ({{ $startTime->addMinutes(10)->format('h:i A') }})</span>
                    @endif

                    @if($class->slides_path)
                    <a href="{{ asset($class->slides_path) }}" download class="block mt-2 text-center text-xs font-bold text-yellow-500 hover:text-yellow-400">
                        <i class="fas fa-download mr-1"></i> Download Slides
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <p class="text-gray-500">No live classes scheduled.</p>
            </div>
            @endforelse
        </div>
    </div>



</div>

<!-- Right Column: Quick Actions & Calendar (Mock) -->
<div class="space-y-8">

    <!-- Upcoming Events Widget -->
    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
    <div class="glass-card p-6 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
        <h3 class="text-xl font-bold text-white mb-6">Upcoming Events</h3>
        <div class="space-y-4">
            @foreach($upcomingEvents as $event)
            <div class="group flex items-start space-x-4 p-3 rounded-xl hover:bg-white/5 transition-colors border border-transparent hover:border-gray-700">
                <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 bg-gray-800 rounded-xl border border-gray-700 group-hover:border-blue-500/50 transition-colors">
                    <span class="text-xs font-bold text-red-400 uppercase">{{ $event->event_date->format('M') }}</span>
                    <span class="text-lg font-bold text-white">{{ $event->event_date->format('d') }}</span>
                </div>
                <div>
                    <h4 class="text-gray-200 font-semibold group-hover:text-blue-400 transition-colors">{{ $event->title }}</h4>
                    <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $event->description }}</p>
                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="far fa-clock mr-1"></i> {{ $event->event_date->format('h:i A') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif



    <!-- Transport Widget -->
    @if(isset($transport) && $transport)
    <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:scale-150 transition-transform"></div>
        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bus text-amber-500"></i> Transport
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-700/50 pb-2">
                <span class="text-gray-400 text-sm">Route</span>
                <span class="text-white font-medium text-right">{{ $transport->route->route_name ?? 'Unknown' }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-700/50 pb-2">
                <span class="text-gray-400 text-sm">Pickup Point</span>
                <span class="text-white font-medium text-right">{{ $transport->pickup_point ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400 text-sm">Monthly Fee</span>
                <span class="text-amber-400 font-bold text-right">PKR {{ number_format($transport->monthly_fee) }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="glass-card p-6 rounded-3xl">
        <h3 class="text-xl font-bold text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('student.online-classes.index') }}" class="p-4 rounded-xl bg-gray-800/50 hover:bg-red-600/20 hover:text-red-400 transition-all group text-center block">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-700 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Join Class</span>
            </a>
            <a href="{{ route('student.exams.admit-card') }}" class="p-4 rounded-xl bg-gray-800/50 hover:bg-blue-600/20 hover:text-blue-400 transition-all group text-center block">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-700 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fas fa-id-card-clip text-xl"></i>
                </div>
                <span class="text-sm font-medium">Admit Card</span>
            </a>
            <button class="p-4 rounded-xl bg-gray-800/50 hover:bg-purple-600/20 hover:text-purple-400 transition-all group text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-700 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Calendar</span>
            </button>
            <button class="p-4 rounded-xl bg-gray-800/50 hover:bg-orange-600/20 hover:text-orange-400 transition-all group text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-700 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Support</span>
            </button>
        </div>
    </div>


</div>
</div>
@endsection