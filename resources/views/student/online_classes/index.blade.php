@extends('layouts.student')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Online Classes</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Join your scheduled virtual classrooms.</p>
    </div>
</div>
@endsection

@section('content')

<!-- Classes Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in">
    @forelse($onlineClasses as $class)
    @php
    // Time Logic
    $startTime = \Carbon\Carbon::parse($class->start_time);
    $endTime = $startTime->copy()->addMinutes($class->duration);

    // "Early Join" Logic: 10 minutes before start time
    $canJoin = now()->greaterThanOrEqualTo($startTime->subMinutes(10)) && now()->lessThanOrEqualTo($endTime);
    $isEnded = now()->greaterThan($endTime);
    $isFuture = now()->lessThan($startTime->subMinutes(10));
    @endphp

    <div class="glass-card p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 transition-colors group relative overflow-hidden">
        <!-- Status Badge -->
        <div class="absolute top-4 right-4">
            @if($canJoin)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 animate-pulse">
                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Live Now
            </span>
            @elseif($isEnded)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                Ended
            </span>
            @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                Upcoming
            </span>
            @endif
        </div>

        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-video"></i>
            </div>

            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-1 group-hover:text-blue-500 transition-colors">
                    {{ $class->topic }}
                </h3>
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4 gap-4">
                    <span class="flex items-center"><i class="fa-regular fa-bookmark mr-2 text-blue-400"></i> {{ $class->subject->name }}</span>
                    <span class="flex items-center"><i class="fa-regular fa-user mr-2 text-purple-400"></i> {{ $class->teacher->name }}</span>
                </div>

                <!-- Timing Details -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3 mb-4 flex items-center justify-between border border-gray-100 dark:border-gray-700">
                    <div class="text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Start</span>
                        <span class="block text-sm font-bold text-gray-800 dark:text-white">{{ $class->start_time->format('h:i A') }}</span>
                    </div>
                    <div class="flex-1 px-4 flex items-center">
                        <div class="h-px bg-gray-300 w-full relative">
                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2 text-xs text-gray-400">
                                {{ $class->duration }} mins
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">End</span>
                        <span class="block text-sm font-bold text-gray-800 dark:text-white">{{ $endTime->format('h:i A') }}</span>
                    </div>
                </div>

                <!-- Action Button -->
                @if($canJoin)
                <a href="{{ $class->join_url }}" target="_blank" class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] shadow-lg shadow-green-500/30">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Join Class Now
                </a>
                @elseif($isEnded)
                <button disabled class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed">
                    Class Ended
                </button>
                @else
                <button disabled class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-400 font-bold rounded-xl cursor-not-allowed border border-gray-200 dark:border-gray-700">
                    <i class="fa-regular fa-clock mr-2"></i> Starts in {{ now()->diffForHumans($startTime, ['parts' => 2, 'short' => true]) }}
                </button>
                @endif

                @if($class->slides_path)
                <a href="{{ asset($class->slides_path) }}" download class="mt-3 w-full inline-flex justify-center items-center px-6 py-3 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold rounded-xl border border-yellow-500/30 hover:bg-yellow-500/20 transition-colors">
                    <i class="fa-solid fa-file-arrow-down mr-2"></i> Download Lecture Slides
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="lg:col-span-2 text-center py-12">
        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-video-slash text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No Online Classes Found</h3>
        <p class="text-gray-500">You don't have any scheduled online classes at the moment.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $onlineClasses->links() }}
</div>

@endsection