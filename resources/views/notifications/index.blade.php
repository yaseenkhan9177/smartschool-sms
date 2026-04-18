@extends($layout)

@section('content')
<div class="p-6 bg-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">Notifications</h1>
                <p class="text-slate-400 text-sm mt-1">Stay updated with your latest alerts</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700">
                <i class="fa-regular fa-bell text-slate-400"></i>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
            <div class="bg-slate-800 rounded-xl p-5 border {{ $notification->read_at ? 'border-slate-700/50' : 'border-blue-500/30 bg-blue-500/5' }} transition-all hover:border-slate-600 group">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-1">
                        @php
                        $type = $notification->data['type'] ?? 'info';
                        $isRead = $notification->read_at;

                        $iconClass = match($type) {
                        'assignment' => 'fa-solid fa-book',
                        'alert' => 'fa-solid fa-triangle-exclamation',
                        'success' => 'fa-solid fa-check-circle',
                        default => $isRead ? 'fa-regular fa-envelope-open' : 'fa-solid fa-envelope'
                        };

                        $colorClass = match($type) {
                        'assignment' => 'bg-blue-500/10 text-blue-400',
                        'alert' => 'bg-red-500/10 text-red-400',
                        'success' => 'bg-emerald-500/10 text-emerald-400',
                        default => $isRead ? 'bg-slate-700/50 text-slate-500' : 'bg-slate-500/10 text-slate-400'
                        };

                        // Override default unread color if generic
                        if (!$isRead && $type === 'info') {
                        $colorClass = 'bg-blue-500/10 text-blue-400';
                        }
                        @endphp

                        <div class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center relative">
                            <i class="{{ $iconClass }}"></i>
                            @if(!$isRead)
                            <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-blue-500 rounded-full border-2 border-slate-800"></span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold {{ $notification->read_at ? 'text-slate-300' : 'text-white' }}">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                <p class="text-slate-400 text-sm mt-1 leading-relaxed">
                                    {{Str::limit( $notification->data['message'] ?? '', 150) }}
                                </p>
                            </div>

                            <!-- Meta & Actions -->
                            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                <span class="text-xs text-slate-500 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>

                                @if(!$notification->read_at)
                                <a href="{{ route($readRouteName, $notification->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-medium transition-colors border border-slate-600 hover:border-slate-500"
                                    title="Mark as Read">
                                    <i class="fa-solid fa-check text-emerald-400"></i> Mark Read
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="text-center py-16 bg-slate-800/50 rounded-2xl border border-slate-700 border-dashed">
                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
                    <i class="fa-regular fa-bell-slash text-slate-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">No notifications yet</h3>
                <p class="text-slate-400 max-w-sm mx-auto">When you receive alerts regarding your classes, exams, or fees, they will appear here.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection