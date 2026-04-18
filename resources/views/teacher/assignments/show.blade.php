@extends('layouts.teacher')

@section('content')
    <div class="mb-6">
        <a href="{{ route('teacher.assignments.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">&larr; Back to Assignments</a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $assignment->title }}</h2>
                <div class="flex space-x-4 text-sm text-gray-600 mb-4">
                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ $assignment->schoolClass->name }}</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $assignment->subject->name }}</span>
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Due: {{ $assignment->due_date->format('M d, Y H:i') }}</span>
                </div>
            </div>
            @if($assignment->file_path)
                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                    <span>Download Attachment</span>
                </a>
            @endif
        </div>
        
        <div class="prose max-w-none text-gray-700">
            <h3 class="text-lg font-semibold mb-2">Description</h3>
            <p>{{ $assignment->description }}</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Student Submissions</h3>
        </div>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Student Name
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Submitted At
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        File
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Grade
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignment->submissions as $submission)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $submission->student->name }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $submission->submitted_at->format('M d, Y H:i') }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Download</a>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="text-gray-500">Not Graded</span> <!-- Placeholder for grading -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            No submissions yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
