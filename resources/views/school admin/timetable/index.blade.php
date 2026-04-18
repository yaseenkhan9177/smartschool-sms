@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">School Timetable</h1>
            <p class="text-gray-500 text-sm mt-1">Master schedule overview for all classes.</p>
        </div>
        <a href="{{ route('admin.timetable.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Create New Schedule
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        @endphp

        @foreach($days as $day)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-purple-500"></i>
                    {{ $day }}
                </h3>
                <span class="text-xs font-medium text-gray-400 bg-white px-2 py-1 rounded-lg border border-gray-100">
                    {{ isset($timetableData[$day]) ? $timetableData[$day]->count() : 0 }} Classes
                </span>
            </div>

            <div class="p-6">
                @if(isset($timetableData[$day]) && $timetableData[$day]->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($timetableData[$day] as $slot)
                    <div class="relative group bg-white border border-gray-200 rounded-xl p-4 hover:border-purple-200 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div class="bg-purple-50 text-purple-700 text-xs font-bold px-2 py-1 rounded-lg">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                            </div>
                            <button class="text-gray-300 hover:text-blue-600 transition-colors" title="Edit Slot (Coming Soon)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>

                        <h4 class="font-bold text-gray-900 mb-1">{{ $slot->subject->name ?? 'Unknown Subject' }}</h4>

                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <i class="fa-solid fa-user-tie text-gray-400"></i>
                            <span class="truncate">{{ $slot->teacher->name ?? 'Unknown Teacher' }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-lg w-fit">
                            <i class="fa-solid fa-layer-group text-gray-400"></i>
                            {{ $slot->schoolClass->name ?? 'Unknown Class' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-gray-400 text-sm">No classes scheduled for {{ $day }}</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection