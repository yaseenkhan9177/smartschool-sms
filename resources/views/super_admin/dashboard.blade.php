@extends('layouts.super_admin')

@section('title', 'SaaS Master Panel')

@section('content')
<div class="px-6 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">SaaS Master Dashboard</h1>
        <p class="text-gray-500">Overview of all schools and subscriptions.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Active Schools -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 bg-blue-50 rounded-xl mr-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $activeSchools }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Active Schools</p>
            </div>
        </div>


    </div>

    <!-- Visitor Traffic Chart -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Visitor Traffic (Last 7 Days)</h2>
        <div class="h-64">
            <canvas id="visitorChart" data-dates="{{ json_encode($dates) }}" data-visits="{{ json_encode($visits) }}"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('visitorChart');
            const dates = JSON.parse(canvas.dataset.dates);
            const visits = JSON.parse(canvas.dataset.visits);
            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Visitors',
                        data: visits,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                borderDash: [2, 4],
                                color: '#e2e8f0'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>

    <!-- Schools Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Schools Management</h2>
            <a href="{{ route('super_admin.create_school') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">+ Add New School</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">School Name</th>
                        <th class="px-6 py-4 font-semibold">Admin (Principal)</th>
                        <th class="px-6 py-4 font-semibold">Contact</th>
                        <th class="px-6 py-4 font-semibold text-center">License Status</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schools as $school)
                    @php
                        $daysRemaining = null;
                        if ($school->license) {
                            $expiryDate = \Carbon\Carbon::parse($school->license->expiry_date);
                            $daysRemaining = (int) now()->diffInDays($expiryDate, false);
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                    {{ substr($school->school_name ?? 'S', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <p class="font-medium text-gray-900">{{ $school->school_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">ID: #{{ $school->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $school->name }}</p>
                            <p class="text-xs text-gray-500">{{ $school->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($daysRemaining !== null)
                                <div class="flex flex-col items-center">
                                    @if($daysRemaining <= 0)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                            Expired
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                                            {{ $daysRemaining <= 2 ? 'bg-red-100 text-red-700 border border-red-200' : ($daysRemaining <= 7 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                                            {{ $daysRemaining }} Days Left
                                        </span>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">Expires: {{ $school->license->expiry_date->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">No Active License</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $school->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $school->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($school->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <!-- View Details Button -->
                            <a href="{{ route('super_admin.schools.show', $school->id) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-600 shadow-sm text-xs font-medium rounded text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="View Details">
                                <i class="fas fa-eye mr-1"></i>
                                View
                            </a>

                            <!-- Impersonate Button -->
                            <a href="{{ route('super_admin.impersonate', $school->id) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 shadow-sm text-xs font-medium rounded text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" title="Login as Admin">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Login
                            </a>

                            <!-- Toggle Status Button -->
                            <form action="{{ route('super_admin.toggle_status', $school->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    @if($school->status === 'active')
                                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Suspend
                                    @else
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Activate
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No schools registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection