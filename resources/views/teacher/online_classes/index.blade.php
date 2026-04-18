@extends('layouts.teacher')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">My Online Classes</h1>
        <a href="{{ route('teacher.online-classes.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20">
            <i class="fa-solid fa-plus mr-2"></i> Schedule Class
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Topic</th>
                        <th class="px-6 py-4 font-semibold">Class / Subject</th>
                        <th class="px-6 py-4 font-semibold">Time</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($classes as $class)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $class->topic }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $class->meeting_id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $class->schoolClass->name }}</div>
                            <div class="text-xs text-purple-600">{{ $class->subject->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $class->start_time->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $class->start_time->format('h:i A') }} ({{ $class->duration }} mins)</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($class->start_time->isPast() && $class->start_time->addMinutes($class->duration)->isFuture())
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold animate-pulse">Live Now</span>
                            @elseif($class->start_time->isPast())
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-bold">Ended</span>
                            @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">Upcoming</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="javascript:void(0)" onclick="confirmStart('{{ $class->start_url }}')" class="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold hover:bg-purple-200 transition-colors">
                                    Start
                                </a>
                                <button type="button" onclick="confirmDelete('{{ $class->id }}')" class="px-2 py-1.5 hover:bg-red-50 text-red-600 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $class->id }}" action="{{ route('teacher.online-classes.destroy', $class->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-video text-gray-400 text-2xl"></i>
                            </div>
                            <p>No online classes scheduled.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmStart(url) {
        Swal.fire({
            title: 'Start Class?',
            text: "Are you sure you want to start this online class now?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, start it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(url, '_blank');
            }
        })
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Cancel Class?',
            text: "Are you sure you want to cancel this class? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection