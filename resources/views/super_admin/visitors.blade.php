@extends('layouts.super_admin')

@section('title', 'Site Visitors')

@section('content')
<div class="px-6 py-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Site Visitors</h1>
            <p class="text-gray-500">Real-time log of website visitors.</p>
        </div>
        <a href="{{ route('super_admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">&larr; Back to Dashboard</a>
    </div>

    <!-- Visitors Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Visit Date</th>
                        <th class="px-6 py-4 font-semibold">IP Address</th>
                        <th class="px-6 py-4 font-semibold">Location</th>
                        <th class="px-6 py-4 font-semibold">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($visitors as $visitor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $visitor->ip_address }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($visitor->location)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $visitor->location }}
                            </span>
                            @else
                            <span class="text-gray-400">Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($visitor->created_at)->format('h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No visitors recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($visitors->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $visitors->links() }}
        </div>
        @endif
    </div>
</div>
@endsection