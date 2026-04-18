@extends('layouts.accountant')

@section('title', 'Generate Student DMCs')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <span class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                <i class="fa-solid fa-file-invoice"></i>
            </span>
            Generate Student DMCs
        </h1>
        <p class="text-gray-500 mt-2 ml-11">Select a class and exam term below to view and print student Detailed Marks Certificates.</p>
    </div>

    <!-- Selection Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-filter text-purple-500"></i> Selection Criteria
            </h2>
        </div>

        <div class="p-8">
            <form action="{{ route('accountant.results.show_class') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Exam Term -->
                    <div class="group">
                        <label for="term_id" class="block text-sm font-semibold text-gray-700 mb-2 group-hover:text-purple-600 transition-colors">Exam Term</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar text-gray-400"></i>
                            </span>
                            <select name="term_id" id="term_id" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all appearance-none cursor-pointer hover:bg-white" required>
                                @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ ($activeTerm && $activeTerm->id == $term->id) ? 'selected' : '' }}>
                                    {{ $term->name }} ({{ \Carbon\Carbon::parse($term->start_date)->format('M d, Y') }})
                                </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Class -->
                    <div class="group">
                        <label for="class_id" class="block text-sm font-semibold text-gray-700 mb-2 group-hover:text-purple-600 transition-colors">Class</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chalkboard-user text-gray-400"></i>
                            </span>
                            <select name="class_id" id="class_id" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all appearance-none cursor-pointer hover:bg-white" required>
                                <option value="">Select a Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="group flex items-center gap-3 px-8 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition-all transform hover:-translate-y-0.5">
                        <span>Fetch Students</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection