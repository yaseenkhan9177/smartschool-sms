@extends('layouts.teacher')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Schedule Online Class</h2>
            <p class="text-gray-500 text-sm mt-1">Create a Zoom meeting for your students.</p>
        </div>

        <form action="{{ route('teacher.online-classes.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                <select name="school_class_id" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <select name="subject_id" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
                <input type="text" name="topic" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500" placeholder="e.g. Algebra Introduction">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="datetime-local" name="start_time" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Minutes)</label>
                    <input type="number" name="duration" min="15" max="240" step="15" value="45" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Lecture Slides (Optional)</label>
                <input type="file" name="slides" class="w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-purple-50 file:text-purple-700
                    hover:file:bg-purple-100
                  " accept=".pdf,.ppt,.pptx,.doc,.docx">
                <p class="mt-1 text-xs text-gray-500">Max size: 10MB (PDF, PPT, Word)</p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20">
                    <i class="fa-solid fa-video mr-2"></i> Create Zoom Meeting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection