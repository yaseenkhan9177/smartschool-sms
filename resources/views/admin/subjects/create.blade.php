@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 mb-6 transition-colors">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Subjects
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                    <i class="fa-solid fa-book-medical"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Add New Subject</h3>
                    <p class="text-sm text-gray-500">Enter the details of the new subject.</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Subject Name</label>
                        <div class="relative">
                            <i class="fa-solid fa-book absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="text" name="name" id="name" required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
                                placeholder="e.g. Mathematics">
                        </div>
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Subject Code</label>
                        <div class="relative">
                            <i class="fa-solid fa-barcode absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="text" name="code" id="code" required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
                                placeholder="e.g. MATH101">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.subjects.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                        Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection