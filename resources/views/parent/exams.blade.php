@extends('layouts.parent')

@section('content')

<!-- Page Title & Back Button -->
<div class="flex flex-col md:flex-row justify-between items-end mb-8 no-print">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Exam Schedule</h2>
        <p class="text-sm text-gray-500 mt-1">View upcoming exams and datesheets for {{ $currentStudent->name }}.</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(!$activeTerm)
<div class="glass-card p-12 text-center rounded-2xl bg-white border border-gray-100 shadow-sm">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fa-solid fa-calendar-xmark text-3xl text-gray-400"></i>
    </div>
    <h3 class="text-lg font-bold text-gray-800 mb-2">No Active Exam Session</h3>
    <p class="text-gray-500">There are currently no active exams scheduled for this term.</p>
</div>
@elseif($schedule->isEmpty())
<div class="glass-card p-12 text-center rounded-2xl bg-white border border-gray-100 shadow-sm">
    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fa-solid fa-hourglass-half text-3xl text-blue-400"></i>
    </div>
    <h3 class="text-lg font-bold text-gray-800 mb-2">Schedule Pending</h3>
    <p class="text-gray-500">The exam schedule for <strong>{{ $activeTerm->name }}</strong> has not been published yet.</p>
</div>
@else

<!-- Exam Rules Card -->
@if($activeTerm->rules)
<div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-6 mb-8 relative overflow-hidden">
    <!-- Decoration -->
    <i class="fa-solid fa-triangle-exclamation absolute -right-4 -bottom-4 text-8xl text-yellow-100 opacity-50"></i>
    <div class="relative z-10">
        <h4 class="text-lg font-bold text-yellow-800 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i> Exam Instructions
        </h4>
        <div class="text-sm text-yellow-900/80 leading-relaxed whitespace-pre-line">{{ $activeTerm->rules }}</div>
    </div>
</div>
@endif

<!-- Main Schedule Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 print:bg-white print:border-b-2 print:border-black">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-indigo-500 rounded-full no-print"></span>
            {{ $activeTerm->name }}
        </h3>
        <button onclick="window.print()" class="no-print text-sm font-bold text-gray-600 hover:text-indigo-600 flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-indigo-200 shadow-sm transition">
            <i class="fa-solid fa-print"></i> Print / Download
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 border-b border-gray-100 print:bg-white print:border-black print:text-black">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Time</th>
                    <th class="px-6 py-4">Details</th>
                    <th class="px-6 py-4 no-print">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 print:divide-black">
                @foreach($schedule as $exam)
                @php
                $isToday = $exam->exam_date->isToday();
                $isPast = $exam->exam_date->isPast() && !$isToday;
                $rowClass = $isToday ? 'bg-yellow-50/50 ring-1 ring-inset ring-yellow-200' : ($isPast ? 'bg-gray-50/50 grayscale opacity-75' : 'hover:bg-blue-50/30 transition-colors');
                @endphp
                <tr class="{{ $rowClass }} print:bg-white">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="block font-bold text-gray-800 text-base print:text-black">{{ $exam->exam_date->format('d M') }}</span>
                        <span class="text-xs text-indigo-500 font-bold uppercase tracking-wide print:text-black">{{ $exam->exam_date->format('l') }}</span>
                        <span class="text-xs text-gray-400 block print:hidden">{{ $exam->exam_date->format('Y') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-bold text-gray-800 text-base print:text-black">{{ $exam->subject->name }}</div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100 print:border-black print:text-black print:bg-white">
                            {{ $exam->paper_type ?? 'Theory' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2 text-gray-700 font-mono text-sm bg-gray-100 px-3 py-1.5 rounded-lg w-fit print:bg-white print:border print:border-black print:text-black">
                            <i class="fa-regular fa-clock text-gray-400 text-xs print:hidden"></i>
                            {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 print:text-black">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-door-closed text-gray-400 w-4 text-center print:hidden"></i>
                            <span>Room: <strong class="text-gray-900 print:text-black">{{ $exam->room ?? 'TBA' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-gray-400 w-4 text-center print:hidden"></i>
                            <span>Max Marks: {{ $exam->total_marks }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap no-print">
                        @if($isPast)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                            <i class="fa-solid fa-check"></i> Completed
                        </span>
                        @elseif($isToday)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 animate-pulse">
                            <i class="fa-solid fa-bell"></i> Today
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                            <i class="fa-solid fa-calendar"></i> Upcoming
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection