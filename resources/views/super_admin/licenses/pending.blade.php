@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pending Renewals</h1>
            <p class="text-sm text-gray-500">Review and approve auto-generated license keys</p>
        </div>
        <a href="{{ route('super_admin.licenses.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Active Licenses
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Pending Keys Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-amber-50/50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-hourglass-half text-amber-500"></i>
                Draft Keys ({{ $pendingKeys->count() }})
            </h2>
            <form action="#" method="GET"> <!-- Placeholder for bulk actions if needed -->
                <!-- <button class="text-sm text-blue-600 hover:underline">Approve All</button> -->
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs uppercase tracking-wider text-gray-500 font-medium border-b border-gray-100">
                        <th class="px-6 py-4">School</th>
                        <th class="px-6 py-4">Draft License Key</th>
                        <th class="px-6 py-4">Proposed Plan</th>
                        <th class="px-6 py-4">Generated Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingKeys as $key)
                    <tr class="hover:bg-amber-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800 block">{{ $key->school->school_name ?? 'Unknown School' }}</span>
                            <span class="text-xs text-gray-500">{{ $key->school->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <code class="bg-white border border-gray-200 text-gray-600 px-2 py-1 rounded text-xs font-mono select-all blur-[2px] hover:blur-none transition-all cursor-pointer" title="Hover to reveal">{{ $key->license_key }}</code>
                                <span class="text-[10px] text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded">Auto-Gen</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $key->plan_duration) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $key->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('super_admin.licenses.activate', $key->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm shadow-blue-500/20 transition-all flex items-center gap-2 ml-auto">
                                    <i class="fa-solid fa-check"></i> Approve & Send
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-clipboard-check text-4xl mb-3 text-gray-300"></i>
                                <p>No pending draft keys.</p>
                                <p class="text-xs text-gray-400 mt-1">Automatic drafts will appear here when licenses are expiring.</p>
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