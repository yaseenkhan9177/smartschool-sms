@extends('layouts.admin')

@section('title', 'Manage Teachers')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage Teachers</h1>
        <p class="text-gray-500 mt-1">View, search, and manage all teachers.</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 items-center">
        <!-- Search Form -->
        <form action="{{ route('admin.teachers') }}" method="GET" class="w-full sm:w-64">
            <div class="relative">
                <input type="text" name="search" placeholder="Search teachers..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </form>

        <a href="{{ route('admin.teachers.create') }}" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center">
            <i class="fa-solid fa-plus mr-2"></i> Register Teacher
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 flex items-center gap-3 animate-fade-in-down">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4">Teacher</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Assigned Classes</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($teachers as $teacher)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($teacher->image)
                            <img src="{{ asset('uploads/'.$teacher->image) }}" alt="" class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-200">
                            @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                                {{ substr($teacher->name, 0, 1) }}
                            </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $teacher->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $teacher->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium">
                            {{ $teacher->subject }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse($teacher->schoolClasses as $class)
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $class->name }}
                            </span>
                            @empty
                            <span class="text-gray-400 text-xs italic">None</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View Profile">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Update Record">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('admin.teachers.delete', $teacher->id) }}" onclick="return confirm('Are you sure you want to delete this teacher?')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300 text-2xl">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                            <p class="font-medium">No teachers found</p>
                            <p class="text-xs mt-1">Get started by adding a new teacher.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $teachers->links() }}
</div>
@endsection