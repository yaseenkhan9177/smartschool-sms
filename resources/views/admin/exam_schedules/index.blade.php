@extends(request()->routeIs('accountant.*') ? 'layouts.accountant' : 'layouts.admin')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Exam Schedules</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review and publish exam dates for each class.</p>
    </div>
    <a href="{{ route($routePrefix . '.exam-schedules.create') }}" class="px-6 py-2.5 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-all shadow-lg shadow-purple-500/30 flex items-center">
        <i class="fa-solid fa-plus mr-2"></i> Schedule New Exam
    </a>
</div>
@endsection

@section('content')

@if($schedules->isEmpty())
<div class="glass-card p-12 text-center rounded-2xl border border-gray-200 dark:border-gray-700">
    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fa-solid fa-calendar-plus text-3xl text-gray-400"></i>
    </div>
    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">No Exams Scheduled Yet</h3>
    <p class="text-gray-500 dark:text-gray-400 mb-6">Start by scheduling an exam for a class.</p>
    <a href="{{ route($routePrefix . '.exam-schedules.create') }}" class="text-purple-600 font-bold hover:underline">Create First Schedule</a>
</div>
@else

<div class="glass-card p-6 rounded-2xl border border-gray-200 dark:border-gray-700 mb-8">
    <form method="GET" action="{{ route($routePrefix . '.exam-schedules.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Term</label>
            <select name="term_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500" onchange="this.form.submit()">
                <option value="">-- All Terms --</option>
                @foreach($terms as $term)
                <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Class</label>
            <select name="class_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500" onchange="this.form.submit()">
                <option value="">-- All Classes --</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ route($routePrefix . '.exam-schedules.index') }}" class="text-sm text-gray-500 hover:text-purple-500 underline">Clear Filters</a>
        </div>
    </form>
</div>

@foreach($schedules->groupBy('class_id') as $classId => $exams)
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden">
    <!-- Card Header -->
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50 dark:bg-gray-700/20">
        <div>
            <h5 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                Class: {{ $exams->first()->class->name }}
            </h5>
            <p class="text-xs text-gray-500 ml-4 mt-1">{{ $exams->count() }} Exams Scheduled</p>
        </div>

        @if(!$exams->first()->is_published)
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 text-xs font-bold rounded-full border border-yellow-200 dark:border-yellow-800 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                Draft
            </span>
            <a href="{{ route($routePrefix . '.exams.publish', $classId) }}" class="px-5 py-2 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20 flex items-center" onclick="return confirm('Are you sure you want to publish the schedule for this class?');">
                <i class="fa-solid fa-check-circle mr-2"></i> Publish to Students
            </a>
        </div>
        @else
        <span class="px-4 py-1.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 text-xs font-bold rounded-full border border-green-200 dark:border-green-800 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Live & Visible
        </span>
        @endif
    </div>

    <!-- Card Body -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-white dark:bg-gray-800 text-xs uppercase font-bold text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3">Subject / Details</th>
                    <th class="px-6 py-3">Timing</th>
                    <th class="px-6 py-3">Room / Sup</th>
                    <th class="px-6 py-3 text-center">Marks</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @foreach($exams as $exam)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 dark:text-white">{{ $exam->subject->name }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                {{ $exam->paper_type ?? 'Theory' }}
                            </span>
                            @if($exam->section)
                            <span class="text-xs text-gray-400">Sec: {{ $exam->section }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-700 dark:text-gray-300 font-medium">{{ $exam->exam_date->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-door-open text-gray-400 text-xs w-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $exam->room ?? 'N/A' }}</span>
                        </div>
                        @if($exam->supervisor)
                        <div class="flex items-center gap-2" title="Supervisor">
                            <i class="fa-solid fa-user-tie text-gray-400 text-xs w-4"></i>
                            <span class="text-xs text-gray-500">{{ $exam->supervisor->name }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $exam->total_marks }}</span>
                            <span class="text-[10px] text-gray-400 border-t border-gray-200 mt-0.5 pt-0.5 w-8">Pass: {{ $exam->passing_marks }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route($routePrefix . '.exam-schedules.destroy', $exam->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this exam?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-colors"><i class="fa-regular fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endif

@endsection