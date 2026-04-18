@extends('layouts.student')

@section('header')
My Assignments 📚
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($assignments as $assignment)
    <div class="glass-card p-6 rounded-2xl hover:bg-white/5 transition-colors group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-600/20 to-transparent rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>

        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    {{ $assignment->subject->name ?? 'Unknown Subject' }}
                </span>
                <span class="text-xs text-gray-400">
                    Due: {{ $assignment->due_date->format('M d, Y') }}
                </span>
            </div>

            <h3 class="text-xl font-bold text-white mb-2">{{ $assignment->title }}</h3>
            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $assignment->description }}</p>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $assignment->teacher->name ?? 'Unknown Teacher' }}
                </div>
                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-white">No assignments found</h3>
        <p class="text-gray-400 mt-1">You're all caught up! Check back later for new assignments.</p>
    </div>
    @endforelse
</div>
@endsection