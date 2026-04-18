@extends('layouts.student')

@section('header', 'Attendance Record')

@section('content')
<div class="space-y-6">
    <!-- Month Navigation and Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Calendar Controls -->
        <div class="lg:col-span-3 glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">{{ $date->format('F Y') }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('student.attendance', ['month' => $date->copy()->subMonth()->format('Y-m')]) }}" class="p-2 rounded-lg bg-gray-800 text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="{{ route('student.attendance', ['month' => Carbon\Carbon::now()->format('Y-m')]) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Today
                    </a>
                    <a href="{{ route('student.attendance', ['month' => $date->copy()->addMonth()->format('Y-m')]) }}" class="p-2 rounded-lg bg-gray-800 text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-px bg-gray-700/50 rounded-lg overflow-hidden border border-gray-700">
                <!-- Days Header -->
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="bg-gray-800 py-2 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    {{ $day }}
                </div>
                @endforeach

                <!-- Days -->
                @php
                $daysInMonth = $date->daysInMonth;
                $firstDayOfWeek = $date->copy()->startOfMonth()->dayOfWeek;

                // Previous month pad
                for($i = 0; $i < $firstDayOfWeek; $i++) {
                    echo '<div class="bg-gray-900/50 h-24 sm:h-32"></div>' ;
                    }

                    for($day=1; $day <=$daysInMonth; $day++) {
                    $currentDate=$date->copy()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                    $isToday = $currentDate == Carbon\Carbon::now()->format('Y-m-d');

                    $record = $attendances->get($currentDate);
                    $status = $record ? strtolower($record->status) : null;

                    // Status Colors
                    $bgClass = 'bg-gray-900/30 hover:bg-gray-800/50';
                    $textClass = '';
                    $statusLabel = '';
                    $icon = '';

                    if ($status === 'present' || $status === 'p') {
                    $bgClass = 'bg-green-500/10 hover:bg-green-500/20 border-green-500/20';
                    $textClass = 'text-green-400';
                    $statusLabel = 'Present';
                    $icon = 'fa-check-circle';
                    } elseif ($status === 'absent' || $status === 'a') {
                    $bgClass = 'bg-red-500/10 hover:bg-red-500/20 border-red-500/20';
                    $textClass = 'text-red-400';
                    $statusLabel = 'Absent';
                    $icon = 'fa-times-circle';
                    } elseif ($status === 'late' || $status === 'l') {
                    $bgClass = 'bg-yellow-500/10 hover:bg-yellow-500/20 border-yellow-500/20';
                    $textClass = 'text-yellow-400';
                    $statusLabel = 'Late';
                    $icon = 'fa-clock';
                    } elseif ($status === 'leave' || $status === 'half_day') {
                    $bgClass = 'bg-blue-500/10 hover:bg-blue-500/20 border-blue-500/20';
                    $textClass = 'text-blue-400';
                    $statusLabel = 'Leave';
                    $icon = 'fa-file-alt';
                    }
                    @endphp
                    <div class="relative h-24 sm:h-32 p-2 border border-transparent transition-colors {{ $bgClass }} {{ $isToday ? 'ring-1 ring-indigo-500 z-10' : '' }}">
                        <span class="absolute top-2 left-2 text-sm font-medium {{ $isToday ? 'text-indigo-400' : 'text-gray-500' }}">{{ $day }}</span>

                        @if($status)
                        <div class="mt-6 flex flex-col items-center justify-center space-y-1">
                            <i class="fas {{ $icon }} {{ $textClass }} text-xl"></i>
                            <span class="text-xs font-semibold {{ $textClass }}">{{ $statusLabel }}</span>
                        </div>
                        @endif
                    </div>
                    @php
                    }
                    @endphp
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="glass-card rounded-2xl p-6 h-fit">
            <h3 class="text-lg font-bold text-white mb-4">Summary</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-lg bg-green-500/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="text-gray-300">Present</span>
                    </div>
                    <span class="font-bold text-green-400">{{ $stats['present'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-red-500/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <span class="text-gray-300">Absent</span>
                    </div>
                    <span class="font-bold text-red-400">{{ $stats['absent'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-500/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                        <span class="text-gray-300">Late</span>
                    </div>
                    <span class="font-bold text-yellow-400">{{ $stats['late'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-blue-500/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <span class="text-gray-300">On Leave</span>
                    </div>
                    <span class="font-bold text-blue-400">{{ $stats['leave'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection