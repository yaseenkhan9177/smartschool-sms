@extends('layouts.teacher')

@section('header', 'Create Assignment')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('teacher.assignments.index') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Assignments
        </a>
    </div>

    <div class="glass-card rounded-2xl p-8 relative overflow-hidden">
        <!-- Decorative bg elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -mr-20 -mt-20 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-plus text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">New Assignment</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create a new task for your students.</p>
                </div>
            </div>

            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Title -->
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2" for="title">
                            Assignment Title
                        </label>
                        <input class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                            id="title" type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Algebra Chapter 5 Exercises" required>
                        @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class & Subject Row -->
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2" for="school_class_id">
                            Target Class
                        </label>
                        <div class="relative">
                            <select class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border {{ $errors->has('school_class_id') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} rounded-xl px-4 py-3 appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                id="school_class_id" name="school_class_id" required>
                                <option value="" class="text-gray-400">Select a Class...</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('school_class_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2" for="subject_id">
                            Subject
                        </label>
                        <div class="relative">
                            <select class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border {{ $errors->has('subject_id') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} rounded-xl px-4 py-3 appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                id="subject_id" name="subject_id" required>
                                <option value="">Select a Subject...</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('subject_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2" for="due_date">
                            Due Date & Time
                        </label>
                        <input class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border {{ $errors->has('due_date') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                            id="due_date" type="datetime-local" name="due_date" value="{{ old('due_date') }}" required>
                        @error('due_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachment -->
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            Attachment (Optional)
                        </label>
                        <label class="flex items-center justify-center w-full h-12 px-4 transition bg-white dark:bg-gray-800 border-2 {{ $errors->has('file') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} border-dashed rounded-xl appearance-none cursor-pointer hover:border-indigo-400 focus:outline-none" id="file-drop">
                            <span class="flex items-center space-x-2">
                                <i class="fa-solid fa-cloud-arrow-up text-gray-400"></i>
                                <span class="font-medium text-gray-600 dark:text-gray-300 text-sm" id="file-name">Choose file to upload</span>
                            </span>
                            <input type="file" name="file" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                        </label>
                        @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2" for="description">
                            Instructions / Description
                        </label>
                        <textarea class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200 dark:border-gray-700' }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                            id="description" name="description" rows="5" placeholder="Detailed instructions for the students..." required>{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-4">
                    <a href="{{ route('teacher.assignments.index') }}" class="px-6 py-2.5 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-sm"></i> Publish Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection