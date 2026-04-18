@php
$layout = 'layouts.app';
if(Auth::guard('web')->check()){
$layout = 'layouts.admin';
} elseif(Auth::guard('teacher')->check()){
$layout = 'layouts.teacher';
} elseif(Auth::guard('accountant')->check()){
$layout = 'layouts.accountant';
}
@endphp

@extends($layout)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Staff Meetings</h2>
            <p class="text-sm text-gray-500 mt-1">Upcoming meetings and briefings.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($meetings as $meeting)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                    <i class="fa-solid fa-video text-xl"></i>
                </div>
                @if($meeting->status == 'scheduled')
                <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full border border-yellow-200">Scheduled</span>
                @elseif($meeting->status == 'started')
                <span class="bg-green-50 text-green-700 text-xs font-bold px-2 py-1 rounded-full border border-green-200 animate-pulse">Live Now</span>
                @else
                <span class="bg-gray-50 text-gray-600 text-xs font-bold px-2 py-1 rounded-full border border-gray-200">{{ ucfirst($meeting->status) }}</span>
                @endif
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2 leading-tight">{{ $meeting->topic }}</h3>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $meeting->description ?? 'No description provided.' }}</p>

            <div class="space-y-3 mb-6 flex-grow">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fa-regular fa-calendar w-5 text-gray-400"></i>
                    <span>{{ $meeting->start_time->format('l, d M Y') }}</span>
                </div>
                <div class="flex items-center text-sm text-blue-600 font-medium">
                    <i class="fa-regular fa-clock w-5"></i>
                    <span>{{ $meeting->start_time->format('h:i A') }} ({{ $meeting->duration }} min)</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fa-solid fa-user-shield w-5 text-gray-400"></i>
                    <span>Host: {{ $meeting->host_type == 'admin' ? 'Admin' : 'Teacher' }}</span>
                </div>
            </div>

            @php
            $isTime = now()->greaterThanOrEqualTo($meeting->start_time->subMinutes(5)) && now()->lessThan($meeting->start_time->addMinutes($meeting->duration));
            // Only allow join if it's "Live" or close to start time
            @endphp

            @if($meeting->host_id == session('teacher_id') && $meeting->host_type == 'teacher')
            <a href="{{ route('teacher.meetings.start', $meeting->id) }}" target="_blank" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors text-center shadow-sm">
                Start Meeting
            </a>
            @else
            @if($isTime || $meeting->status == 'started')
            <a href="{{ route('teacher.meetings.join', $meeting->id) }}" target="_blank" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors text-center shadow-lg shadow-green-500/30 animate-pulse">
                Join Now
            </a>
            @elseif(now()->greaterThan($meeting->start_time->addMinutes($meeting->duration)))
            <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 font-bold rounded-lg text-center cursor-not-allowed">
                Ended
            </button>
            @else
            <button disabled class="w-full py-2.5 bg-gray-50 text-gray-400 font-bold rounded-lg text-center cursor-not-allowed border border-gray-200">
                Join (Starts {{ $meeting->start_time->diffForHumans() }})
            </button>
            @endif
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-100 border-dashed">
            <div class="text-gray-300 text-5xl mb-4">
                <i class="fa-regular fa-calendar-xmark"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700">No Meetings Scheduled</h3>
            <p class="text-gray-500 text-sm">You have no upcoming staff meetings.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection