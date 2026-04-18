@extends('layouts.student')

@section('header', 'My Class Schedule')

@section('content')
<!-- Next Class Widget -->
@php
// Helper logic to determing coloring and next class
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$timeSlots = [
'09:00:00' => '09:00 AM',
'10:00:00' => '10:00 AM',
'11:00:00' => '11:00 AM',
'12:00:00' => '12:00 PM',
'13:00:00' => '01:00 PM',
'14:00:00' => '02:00 PM',
];

$colors = [
'bg-blue-500/20 text-blue-300 border-blue-500/30',
'bg-green-500/20 text-green-300 border-green-500/30',
'bg-purple-500/20 text-purple-300 border-purple-500/30',
'bg-orange-500/20 text-orange-300 border-orange-500/30',
'bg-pink-500/20 text-pink-300 border-pink-500/30',
'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
];

$nextClass = null; // Ideally passed from controller, but simple logic here
$now = \Carbon\Carbon::now();

// Simple next class logic
foreach($timetables as $t) {
// simplified for demo
if(!$nextClass) $nextClass = $t;
}
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Next Class Card -->
    <div class="glass-card rounded-3xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
        <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Up Next</h3>

        @if($nextClass)
        <div class="flex items-start justify-between relative z-10">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $nextClass->subject->name }}</h2>
                <p class="text-blue-500 dark:text-blue-400 font-medium flex items-center gap-2">
                    <i class="fa-regular fa-clock"></i>
                    {{ \Carbon\Carbon::parse($nextClass->start_time)->format('h:i A') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl font-bold border border-blue-200 dark:border-blue-500/30">
                {{ substr($nextClass->subject->name, 0, 1) }}
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gray-700 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($nextClass->teacher->name) }}&background=random" class="w-full h-full object-cover">
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Prof. {{ $nextClass->teacher->name }}</p>
        </div>
        @else
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-green-600 dark:text-green-400 text-xl">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">No classes today</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Enjoy your break!</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Weekly Goal -->
    <div class="glass-card rounded-3xl p-6 lg:col-span-2 flex items-center justify-between">
        <div class="flex flex-col justify-center h-full">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Weekly Goal</h3>
            <p class="text-gray-800 dark:text-white text-lg">You have <span class="text-green-500 dark:text-green-400 font-bold">{{ $timetables->count() }} classes</span> scheduled this week.</p>
            <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full mt-4 max-w-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full" style="width: 75%"></div>
            </div>
        </div>
        <div class="hidden sm:block">
            <i class="fa-solid fa-calendar-check text-6xl text-gray-200 dark:text-gray-700/50"></i>
        </div>
    </div>
</div>

<!-- Time-Table Matrix Grid View -->
<div class="glass-card rounded-3xl p-8 animate-fade-in" style="animation-delay: 0.1s;">
    <div class="overflow-x-auto">
        <div class="min-w-[800px]">
            <!-- Header Row (Days) -->
            <div class="grid grid-cols-7 gap-4 mb-4">
                <div class="p-3 text-center text-slate-500 font-bold uppercase text-xs tracking-wider">Time</div>
                @foreach($days as $day)
                <div class="p-3 text-center rounded-xl bg-gray-100 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700/50">
                    <span class="text-gray-700 dark:text-slate-300 font-bold block">{{ substr($day, 0, 3) }}</span>
                </div>
                @endforeach
            </div>

            <!-- Time Slots Rows -->
            @foreach($timeSlots as $slotTime => $slotLabel)
            <div class="grid grid-cols-7 gap-4 mb-4">
                <!-- Time Column -->
                <div class="flex items-center justify-center text-slate-400 font-medium text-sm">
                    {{ $slotLabel }}
                </div>

                <!-- Days Columns -->
                @foreach($days as $day)
                @php
                // Find class logic
                $class = $timetables->filter(function($t) use ($day, $slotTime) {
                return $t->day === $day && substr($t->start_time, 0, 2) === substr($slotTime, 0, 2);
                })->first();

                // Styles
                $style = $class
                ? ($colors[$class->subject->id % 6] . ' hover:scale-105 hover:shadow-lg z-0 hover:z-10 cursor-pointer')
                : 'border-dashed border-gray-200 dark:border-slate-700/50 opacity-50';
                @endphp

                <div class="h-24 rounded-xl border {{ $style }} relative group transition-all p-2 flex flex-col justify-center gap-1">
                    @if($class)
                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-70">{{ substr($class->subject->code, 0, 6) }}</span>
                        @if(\Carbon\Carbon::now()->format('l') == $day && \Carbon\Carbon::now()->format('H') == substr($slotTime, 0, 2))
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse shadow-lg shadow-green-500/50" title="Current Class"></span>
                        @endif
                    </div>
                    <h4 class="text-xs sm:text-sm font-bold leading-tight line-clamp-2">{{ $class->subject->name }}</h4>
                    <div class="flex items-center gap-1.5 mt-auto pt-2 border-t border-white/10">
                        <div class="w-4 h-4 rounded-full bg-slate-700 overflow-hidden shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($class->teacher->name) }}&background=random" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[10px] opacity-80 truncate">{{ $class->teacher->name }}</span>
                    </div>
                    @else
                    <div class="w-full h-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fa-solid fa-plus text-slate-400 dark:text-slate-600"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection