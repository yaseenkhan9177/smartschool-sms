@extends('layouts.student')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Exam Results</h1>
        <p class="text-gray-600 mt-2">View your performance history and detailed marks sheets.</p>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($terms as $term)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $term->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $term->start_date ? $term->start_date->format('M d, Y') : 'N/A' }} - {{ $term->end_date ? $term->end_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        @if($term->is_active)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Active</span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase tracking-wide">Completed</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-500">Subjects Graded:</span>
                        <span class="font-bold text-gray-800 ml-1">{{ $term->examResults->count() }}</span>
                    </div>

                    <a href="{{ route('exam.result.print', ['student_id' => $student->id, 'term_id' => $term->id]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-alt mr-2"></i> View Detailed Result
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No Results Found</h3>
            <p class="text-gray-500">You haven't taken any exams yet, or results haven't been published.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection