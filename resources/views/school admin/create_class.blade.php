@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Add New Class</h1>
        <p class="text-gray-500 mt-1">Create a new class for the school.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Class Name</label>
                <input type="text" name="name" id="name"
                    class="w-full px-4 py-3 rounded-xl {{ $errors->has('name') ? 'bg-red-50 border-red-500' : 'bg-gray-50 border-transparent' }} focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="e.g. Class 1-A" value="{{ old('name') }}" required>
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                    Create Class
                </button>
                <a href="{{ route('admin.classes') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection