@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">License Management</h1>
            <p class="text-sm text-gray-500">Monitor and manage school access keys</p>
        </div>
        <a href="{{ route('super_admin.licenses.create') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-blue-600/20">
            <i class="fa-solid fa-plus"></i>
            Generate Key
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Active Licenses</p>
                <p class="text-3xl font-bold text-gray-800">{{ $activeLicenses }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                <i class="fa-solid fa-check-circle text-xl"></i>
            </div>
        </div>

        <!-- Expired -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Expired Licenses</p>
                <p class="text-3xl font-bold text-gray-800">{{ $expiredLicenses }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pending Renewals</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pendingKeys }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fa-solid fa-clock text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Licenses Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Active Licenses</h2>
            <div class="flex gap-2">
                <button class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fa-solid fa-filter"></i></button>
                <button class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fa-solid fa-download"></i></button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs uppercase tracking-wider text-gray-500 font-medium border-b border-gray-100">
                        <th class="px-6 py-4">School</th>
                        <th class="px-6 py-4">License Key</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Activation</th>
                        <th class="px-6 py-4">Expiry</th>
                        <th class="px-6 py-4">Time Remaining</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($licenses as $license)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800 block">{{ $license->school->school_name ?? 'Unknown School' }}</span>
                            <span class="text-xs text-gray-500">{{ $license->school->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-mono select-all">{{ $license->license_key }}</code>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $license->plan_duration) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $license->start_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $license->expiry_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                            $remaining = \Carbon\Carbon::now()->diffInDays($license->expiry_date, false);
                            $isExpired = $remaining < 0;
                                @endphp

                                @if($isExpired)
                                <span class="text-red-500 font-medium text-xs">Expired {{ abs($remaining) }} days ago</span>
                                @elseif($remaining < 7)
                                    <span class="text-amber-500 font-medium text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $remaining }} days left
                                    </span>
                                    @else
                                    <span class="text-green-600 font-medium text-xs">{{ $remaining }} days</span>
                                    @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($license->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($license->status) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>No active licenses found.</p>
                                <a href="{{ route('super_admin.licenses.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2">Generate one now</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection