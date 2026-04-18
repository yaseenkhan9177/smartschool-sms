@extends('layouts.teacher')

@section('header', 'My Classes')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($teacher->schoolClasses as $class)
    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-chalkboard-user text-xl"></i>
            </div>
            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-full">Class</span>
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $class->name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">View students, attendance, and details for this class.</p>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('teacher.class.show', $class->id) }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center gap-2 group-hover:gap-3 transition-all">
                View Students <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <a href="{{ route('teacher.attendance', $class->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 font-medium text-sm transition-colors">
                <i class="fa-solid fa-clipboard-check"></i> Take Attendance
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 border-dashed">
        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
            <i class="fa-solid fa-chalkboard text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Classes Assigned</h3>
        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">You haven't been assigned to any classes yet. Please contact the administrator.</p>
    </div>
    @endforelse
</div>
@endsection