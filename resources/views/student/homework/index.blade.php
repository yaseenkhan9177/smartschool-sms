@extends('layouts.student')

@section('header', 'My Homework')

@section('content')
<div class="space-y-6">
    <!-- Homework Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($homeworks as $homework)
        <div class="glass-card rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 relative overflow-hidden group">

            <div class="absolute top-0 right-0 p-4 opacity-50 text-6xl text-gray-100 dark:text-gray-700 pointer-events-none transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-book-open"></i>
            </div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $homework->subject->name }}
                    </span>
                    @if($homework->due_date->isPast())
                    <span class="text-xs font-bold text-red-500">Overdue</span>
                    @else
                    <span class="text-xs font-bold text-green-500">{{ $homework->due_date->diffForHumans() }}</span>
                    @endif
                </div>

                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 line-clamp-1">{{ $homework->title }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ $homework->description }}</p>

                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4 mt-auto">
                    <div class="flex items-center" title="Teacher">
                        <i class="fa-solid fa-chalkboard-user mr-2 text-indigo-400"></i> {{ $homework->teacher->name }}
                    </div>
                    <div class="flex items-center" title="Due Date">
                        <i class="fa-regular fa-calendar-xmark mr-2 text-red-400"></i> {{ $homework->due_date->format('M d') }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="w-24 h-24 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-book-open text-gray-300 dark:text-gray-500 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">No Homework Found</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">You don't have any pending homework assignments.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection