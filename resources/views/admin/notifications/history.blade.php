@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Notification History</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Sender</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($notifications as $notification)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $notification->data['sender'] ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                {{ $notification->data['title'] ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $notification->data['message'] ?? '' }}">
                                {{ $notification->data['message'] ?? '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-md text-xs">
                                    {{ $notification->notifiable_type }} (#{{ $notification->notifiable_id }})
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No notification history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
