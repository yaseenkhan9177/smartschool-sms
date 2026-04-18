@extends('layouts.teacher')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Homework</h2>
                <p class="text-gray-500 text-sm mt-1">Update homework details.</p>
            </div>
            <a href="{{ route('teacher.homework.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        <form action="{{ route('teacher.homework.update', $homework->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <div class="relative">
                        <select name="class_id" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 appearance-none py-3 px-4">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $homework->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <div class="relative">
                        <select name="subject_id" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 appearance-none py-3 px-4">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $homework->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Homework Title</label>
                <input type="text" name="title" value="{{ $homework->title }}" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 px-4">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description / Instructions</label>
                <textarea name="description" rows="4" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 px-4">{{ $homework->description }}</textarea>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Date</label>
                    <input type="date" name="assigned_date" value="{{ $homework->assigned_date->format('Y-m-d') }}" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 px-4">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <input type="date" name="due_date" value="{{ $homework->due_date->format('Y-m-d') }}" required class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 px-4">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end gap-3">
                <a href="{{ route('teacher.homework.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 text-white hover:bg-purple-700 font-bold shadow-lg shadow-purple-200 transition-colors">
                    <i class="fa-solid fa-save mr-2"></i> Update Homework
                </button>
            </div>
        </form>
    </div>
</div>
@endsection