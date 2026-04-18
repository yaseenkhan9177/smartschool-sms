@extends('layouts.teacher')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Homework Management</h1>
        <a href="{{ route('teacher.homework.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20">
            <i class="fa-solid fa-plus mr-2"></i> Assign Homework
        </a>
    </div>

    <!-- Homework List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($homeworks as $homework)
        <div class="glass-card rounded-2xl p-6 relative group hover:shadow-lg transition-all border border-gray-100 dark:border-gray-700 bg-white">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <!-- Actions -->
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('teacher.homework.edit', $homework->id) }}" class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button onclick="confirmDelete('{{ $homework->id }}')" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <form id="delete-form-{{ $homework->id }}" action="{{ route('teacher.homework.destroy', $homework->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $homework->title }}</h3>
            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $homework->description }}</p>

            <div class="flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-4">
                <div class="flex items-center">
                    <i class="fa-solid fa-users mr-2 text-indigo-400"></i> {{ $homework->schoolClass->name }}
                </div>
                <div class="flex items-center">
                    <i class="fa-solid fa-layer-group mr-2 text-pink-400"></i> {{ $homework->subject->name }} ({{ $homework->subject->code }})
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between text-xs font-semibold">
                <span class="text-green-600">Assigned: {{ $homework->assigned_date->format('M d') }}</span>
                <span class="text-red-500">Due: {{ $homework->due_date->format('M d') }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-gray-300 text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">No Homework Assigned</h3>
            <p class="text-gray-500 mt-2">Start by assigning homework to your classes.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Homework?',
            text: "Are you sure you want to delete this homework assignment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection