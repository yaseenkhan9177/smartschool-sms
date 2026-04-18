@extends('layouts.teacher')

@section('header', 'Assignments')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">All Assignments</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your class assignments and view submissions.</p>
        </div>
        <a href="{{ route('teacher.assignments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Create New
        </a>
    </div>

    <!-- Assignments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($assignments as $assignment)
        <div class="glass-card rounded-2xl p-6 relative group hover:-translate-y-1 transition-transform duration-300 flex flex-col h-full bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700">
            <!-- Status/Due Badge -->
            <div class="absolute top-4 right-4">
                @if($assignment->due_date->isPast())
                <span class="px-2.5 py-1 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-bold uppercase tracking-wide">Closed</span>
                @else
                <span class="px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-xs font-bold uppercase tracking-wide">Active</span>
                @endif
            </div>

            <!-- Content -->
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide block">
                            {{ $assignment->schoolClass->name }} &bull; {{ $assignment->subject->name }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            <a href="{{ route('teacher.assignments.show', $assignment->id) }}">
                                {{ Str::limit($assignment->title, 40) }}
                            </a>
                        </h3>
                    </div>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 line-clamp-2">
                    {{ $assignment->description }}
                </p>
            </div>

            <!-- Footer Stats -->
            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-sm">
                <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2" title="Due Date">
                    <i class="fa-regular fa-clock text-gray-400"></i>
                    {{ $assignment->due_date->format('M d, h:i A') }}
                </div>
                <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2" title="Submissions">
                    <i class="fa-solid fa-user-check text-gray-400"></i>
                    {{ $assignment->submissions->count() }} Submissions
                </div>
            </div>

            <!-- Action Overlay (Optional or just rely on clicking card) -->
            <div class="mt-4 flex gap-2">
                <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="flex-1 text-center py-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-semibold transition-colors">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center">
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-clipboard-question text-3xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Assignments Created</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                You haven't posted any assignments yet. Click the button below to get started.
            </p>
            <a href="{{ route('teacher.assignments.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-all">
                <i class="fa-solid fa-plus"></i> Create Assignment
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection