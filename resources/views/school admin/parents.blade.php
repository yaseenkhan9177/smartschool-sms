@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Parents</h1>
            <p class="text-gray-500 mt-1">View all registered parents and their children.</p>
        </div>
        <div x-data="{ copied: false }" class="flex gap-2">
            <button @click="navigator.clipboard.writeText('{{ route('parent.registration') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-link'"></i>
                <span x-text="copied ? 'Link Copied!' : 'Admission Link'"></span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Parent Phone</th>
                        <th class="px-6 py-4">Children Count</th>
                        <th class="px-6 py-4">Children Names</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parents as $parent)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                    <i class="fa-solid fa-user-group text-xs"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold border-none">{{ $parent->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $parent->phone }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $parent->students->count() }} Children
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($parent->students as $child)
                                <a href="{{ route('admin.students', ['search' => $child->name]) }}" class="text-xs border border-gray-200 rounded px-2 py-1 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                    {{ $child->name }}
                                </a>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.students', ['search' => $parent->phone]) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="View Students">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                    <i class="fa-solid fa-users-slash text-xl"></i>
                                </div>
                                <p>No parents found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parents->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $parents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection