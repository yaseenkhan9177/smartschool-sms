@extends('layouts.student')

@section('header')
Assignment Details 📝
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('student.assignments.index') }}" class="text-gray-400 hover:text-white transition-colors inline-flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Assignments
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Assignment Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass-card p-8 rounded-3xl">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-3">
                        {{ $assignment->subject->name ?? 'Unknown Subject' }}
                    </span>
                    <h2 class="text-3xl font-bold text-white mb-2">{{ $assignment->title }}</h2>
                    <div class="flex items-center text-gray-400 text-sm space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $assignment->teacher->name ?? 'Unknown Teacher' }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Due: {{ $assignment->due_date->format('M d, Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="prose prose-invert max-w-none mb-8">
                <h3 class="text-lg font-semibold text-white mb-3">Description</h3>
                <p class="text-gray-300 leading-relaxed">{{ $assignment->description }}</p>
            </div>

            @if($assignment->file_path)
            <div class="border-t border-gray-700/50 pt-6">
                <h3 class="text-lg font-semibold text-white mb-3">Attachment</h3>
                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="inline-flex items-center p-4 rounded-xl bg-gray-800/50 hover:bg-gray-800 transition-colors border border-gray-700 group">
                    <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-colors mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-white">Download Attachment</p>
                        <p class="text-xs text-gray-500">Click to view or download</p>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Submission Sidebar -->
    <div class="lg:col-span-1">
        <div class="glass-card p-6 rounded-3xl sticky top-8">
            <h3 class="text-xl font-bold text-white mb-6">Your Submission</h3>

            @if($submission)
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6">
                <div class="flex items-center mb-2">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-green-400">Submitted</span>
                </div>
                <p class="text-xs text-gray-400 ml-9">Submitted on {{ $submission->submitted_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-400 mb-2">Submitted File</h4>
                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="flex items-center p-3 rounded-lg bg-gray-800/50 hover:bg-gray-800 transition-colors border border-gray-700">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm text-gray-300 truncate">View Submission</span>
                </a>
            </div>

            @if($submission->grade)
            <div class="border-t border-gray-700/50 pt-4 mt-4">
                <h4 class="text-sm font-medium text-gray-400 mb-2">Grade</h4>
                <div class="text-2xl font-bold text-white">{{ $submission->grade }}</div>
                @if($submission->feedback)
                <p class="text-sm text-gray-400 mt-2">{{ $submission->feedback }}</p>
                @endif
            </div>
            @else
            <div class="border-t border-gray-700/50 pt-4 mt-4">
                <p class="text-sm text-gray-500 italic">Not graded yet.</p>
            </div>
            @endif

            @else
            <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Upload File</label>
                    <div class="relative border-2 border-dashed border-gray-600 rounded-xl p-6 hover:border-indigo-500 transition-colors text-center cursor-pointer" onclick="document.getElementById('file-upload').click()">
                        <input type="file" name="file" id="file-upload" class="hidden" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                        <svg class="w-8 h-8 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm text-gray-400">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, ZIP, PNG, JPG (Max 2MB)</p>
                        <p id="file-name" class="text-sm text-indigo-400 mt-2 font-medium"></p>
                    </div>
                    @error('file')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                    Submit Assignment
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection