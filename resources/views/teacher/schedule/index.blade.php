@extends('layouts.teacher')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Teaching Schedule</h1>
            <p class="text-gray-500 text-sm mt-1">Your weekly class timetable.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $timetableData = $timetables->groupBy('day');
        @endphp

        @foreach($days as $day)
        @if(isset($timetableData[$day]) && $timetableData[$day]->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-blue-500"></i>
                    {{ $day }}
                </h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($timetableData[$day] as $slot)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-blue-200 hover:shadow-md transition-all border-l-4 border-l-blue-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2 py-1 rounded-lg">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                            </span>
                        </div>

                        <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $slot->schoolClass->name ?? 'Unknown Class' }}</h4>

                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fa-solid fa-book text-gray-400"></i>
                            <span>{{ $slot->subject->name ?? 'Unknown Subject' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @endforeach

        @if($timetables->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-calendar-day text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No Classes Assigned</h3>
            <p class="text-gray-500 mt-1">You do not have any classes scheduled yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection