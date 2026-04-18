@extends('layouts.student')

@section('content')
<div class="max-w-2xl mx-auto py-12 text-center">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-clock-rotate-left text-4xl text-yellow-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Date Sheet Not Released</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            The exam schedule for the current term has not been published yet. <br>
            Please check back later or wait for an official notification from the school.
        </p>
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection