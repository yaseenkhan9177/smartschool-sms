@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Generate New License Key</h1>
            <p class="text-sm text-gray-500">Create a new access key for a school administrator</p>
        </div>
        <a href="{{ route('super_admin.licenses.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('super_admin.licenses.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- School Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select School</label>
                <div class="relative">
                    <select name="school_id" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500 transition-colors" required>
                        <option value="" disabled selected>Choose a school...</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->school_name }} ({{ $school->email }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Plan Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">Plan Duration</label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- 1 Week -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="plan_duration" value="1_week" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-100 bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-white hover:border-blue-200 transition-all text-center">
                            <div class="text-lg font-bold text-gray-700 peer-checked:text-blue-600">1 Week</div>
                            <div class="text-xs text-gray-500 mt-1">Trial Access</div>
                        </div>
                    </label>

                    <!-- 1 Month -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="plan_duration" value="1_month" class="peer sr-only" checked>
                        <div class="p-4 rounded-xl border-2 border-gray-100 bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-white hover:border-blue-200 transition-all text-center">
                            <div class="text-lg font-bold text-gray-700 peer-checked:text-blue-600">1 Month</div>
                            <div class="text-xs text-gray-500 mt-1">Standard Plan</div>
                        </div>
                    </label>

                    <!-- 6 Months -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="plan_duration" value="6_months" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-100 bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-white hover:border-blue-200 transition-all text-center">
                            <div class="text-lg font-bold text-gray-700 peer-checked:text-blue-600">6 Months</div>
                            <div class="text-xs text-gray-500 mt-1">Semi-Annual</div>
                        </div>
                    </label>

                    <!-- 1 Year -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="plan_duration" value="1_year" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-100 bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-white hover:border-blue-200 transition-all text-center">
                            <div class="text-lg font-bold text-gray-700 peer-checked:text-blue-600">1 Year</div>
                            <div class="text-xs text-gray-500 mt-1">Best Value</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-blue-500 transition-colors" required>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Generate & Activate Key
                </button>
            </div>

        </form>
    </div>
</div>
@endsection