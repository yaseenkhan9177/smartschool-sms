@extends('layouts.admin')

@section('title', $school->school_name . ' - Overview')

@section('content')
<div class="px-6 py-6 transition-all duration-300">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('super_admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-700 mb-2 inline-block">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $school->school_name }}</h1>
            <p class="text-gray-500">School ID: #{{ $school->id }} | Admin: {{ $school->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('super_admin.impersonate', $school->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Login as {{ $school->name }}
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Students -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 bg-indigo-50 rounded-xl mr-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalStudents }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Students</p>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 bg-purple-50 rounded-xl mr-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalTeachers }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Teachers</p>
            </div>
        </div>

        <!-- Subscription Status -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 bg-green-50 rounded-xl mr-4">
                @if($school->status === 'active')
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @else
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                @endif
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1 uppercase">{{ $school->status }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Subscription Status</p>
            </div>
        </div>
    </div>

    <!-- Instance Details -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Instance Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Admin Email</p>
                <p class="font-medium">{{ $school->email }}</p>
            </div>
            <div>
                <p class="text-gray-500">Contact Phone</p>
                <p class="font-medium">{{ $school->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Admin Name</p>
                <p class="font-medium">{{ $school->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Account Status</p>
                <p class="font-medium capitalize {{ $school->status === 'active' ? 'text-green-600' : 'text-red-500' }}">{{ $school->status }}</p>
            </div>
        </div>
    </div>

    <!-- Reset Admin Password -->
    <div class="bg-white rounded-2xl shadow-sm border border-amber-200 p-6">
        <div class="flex items-center mb-4">
            <div class="p-2 bg-amber-50 rounded-xl mr-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Reset Admin Password</h3>
                <p class="text-sm text-gray-500">If the admin forgot their password, you can set a new one here.</p>
            </div>
        </div>

        @if(session('password_reset_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('password_reset_success') }}
        </div>
        @endif

        @if($errors->has('new_password'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            {{ $errors->first('new_password') }}
        </div>
        @endif

        <form action="{{ route('super_admin.schools.reset_password', $school->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">New Password for <span class="text-indigo-600">{{ $school->name }}</span></label>
                <input
                    type="text"
                    name="new_password"
                    required
                    minlength="8"
                    placeholder="Enter new password (min 8 chars)"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                >
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-0.5 whitespace-nowrap">
                    Reset Password
                </button>
            </div>
        </form>
        <p class="text-xs text-gray-400 mt-3">⚠️ After resetting, share the new password with the admin directly. Passwords are stored securely (hashed) and cannot be viewed.</p>
    </div>
</div>
@endsection