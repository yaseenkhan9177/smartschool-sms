@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.classes') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $class->name }} Timetable</h1>
                <p class="text-gray-500 mt-1">Weekly schedule for {{ $class->name }}</p>
            </div>
        </div>
        <a href="{{ route('admin.timetable.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Add Schedule
        </a>
    </div>

    <!-- Timetable -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if($timetables->count() > 0)
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Day</th>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Teacher</th>
                        <th class="px-6 py-4 text-right">Actions</th>
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
                                {{ $timetable->subject->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span class="text-gray-900">{{ $timetable->teacher->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
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
                <h4 class="text-gray-900 font-medium mb-1">No Schedule Found</h4>
                <p class="text-gray-500 text-sm">No timetable entries found for this class.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
