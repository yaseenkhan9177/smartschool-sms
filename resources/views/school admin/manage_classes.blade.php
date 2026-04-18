@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Classes</h1>
            <p class="text-gray-500 mt-1">View and manage all classes and their students.</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Add Class
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-chalkboard-user text-xl"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $class->name }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ $class->students_count }} Students Enrolled</p>

            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <div class="flex -space-x-2 overflow-hidden">
                    <!-- Placeholder avatars -->
                    <div class="h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                </div>
                <a href="{{ route('admin.class.students', $class->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    View Students <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end">
                <a href="{{ route('admin.class.timetable', $class->id) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    <i class="fa-regular fa-calendar-days text-xs"></i> View Timetable
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                <i class="fa-solid fa-chalkboard"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No Classes Found</h3>
            <p class="text-gray-500 text-sm mt-1">Get started by creating a new class.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection