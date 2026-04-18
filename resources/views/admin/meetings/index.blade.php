@php
$layout = 'layouts.app'; // Default fallback

if(Auth::guard('web')->check()){ // Admin guard is 'web' in this app usually, or specific 'admin'. The user said 'admin', but in `TeacherMeetingController` it was `web`. earlier `Auth::guard('web')` was used for Admin. I will use `web` to be safe as that is what the controller uses for "Admin". Wait, the directory had `admin.blade.php`.
// Let's stick to what the controller uses: `Auth::guard('web')`.
$layout = 'layouts.admin';
}
elseif(Auth::guard('teacher')->check()){
$layout = 'layouts.teacher';
}
elseif(Auth::guard('accountant')->check()){
$layout = 'layouts.accountant';
}
@endphp

@extends($layout)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Staff Meetings</h2>
            <p class="text-sm text-gray-500 mt-1">Schedule and manage Zoom meetings for teachers.</p>
        </div>
        @if($routePrefix === 'admin')
        <a href="{{ route($routePrefix . '.meetings.create') }}" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 inline-flex items-center">
            <i class="fa-solid fa-video mr-2"></i> Schedule Meeting
        </a>
        @endif
    </div>
       
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Topic / Host</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Participants</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($meetings as $meeting)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 text-base">{{ $meeting->topic }}</div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-user-shield text-blue-500"></i>
                                Host: {{ $meeting->host_type == 'admin' ? 'Admin' : ($meeting->host_type == 'teacher' ? 'Teacher' : 'Unknown') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-700">{{ $meeting->start_time->format('d M, Y') }}</div>
                            <div class="text-xs text-blue-600 font-bold mt-1">
                                {{ $meeting->start_time->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded inline-flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i> {{ $meeting->duration }} mins
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex -space-x-2 overflow-hidden">
                                @foreach($meeting->participants->take(3) as $participant)
                                @if($participant->image)
                                <img class="h-6 w-6 rounded-full ring-2 ring-white object-cover" src="{{ asset($participant->image) }}" alt="" />
                                @else
                                <div class="h-6 w-6 rounded-full ring-2 ring-white bg-blue-100 flex items-center justify-center text-[8px] font-bold text-blue-600">
                                    {{ substr($participant->name, 0, 1) }}
                                </div>
                                @endif
                                @endforeach
                                @if($meeting->participants->count() > 3)
                                <div class="h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-600">
                                    +{{ $meeting->participants->count() - 3 }}
                                </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 mt-1">{{ $meeting->participants->count() }} invited</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($meeting->status == 'scheduled')
                            <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full border border-yellow-200">Scheduled</span>
                            @elseif($meeting->status == 'started')
                            <span class="bg-green-50 text-green-700 text-xs font-bold px-2 py-1 rounded-full border border-green-200 animate-pulse">Live</span>
                            @else
                            <span class="bg-gray-50 text-gray-600 text-xs font-bold px-2 py-1 rounded-full border border-gray-200">{{ ucfirst($meeting->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($meeting->status == 'started')
                                <a href="{{ route($routePrefix . '.meetings.join', $meeting->id) }}" target="_blank" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Join
                                </a>
                                @elseif($routePrefix === 'admin')
                                <a href="{{ route($routePrefix . '.meetings.start', $meeting->id) }}" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-play mr-1"></i> Start
                                </a>
                                @endif
                                @if($routePrefix === 'admin')
                                <form action="{{ route($routePrefix . '.meetings.destroy', $meeting->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this meeting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Cancel Meeting">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="mb-3">
                                <i class="fa-solid fa-video-slash text-4xl text-gray-300"></i>
                            </div>
                            <p>No meetings scheduled yet.</p>
                            @if($routePrefix === 'admin')
                            <a href="{{ route($routePrefix . '.meetings.create') }}" class="text-blue-600 text-sm font-bold hover:underline mt-2 inline-block">Schedule Now</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection