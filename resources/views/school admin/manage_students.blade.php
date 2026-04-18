@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Manage Students</h2>

        <form action="{{ route('admin.students') }}" method="GET" class="flex-1 max-w-md w-full">
            <div class="relative">
                <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </form>

        <div x-data="{ copied: false }" class="flex gap-3 w-full md:w-auto overflow-x-auto">
            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Register Student
            </a>
            <button onclick="alert('Import Feature Coming Soon!')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-csv"></i> Import Excel/CSV
            </button>
            <button @click="navigator.clipboard.writeText('{{ route('parent.registration') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2">
                <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-link'"></i>
                <span x-text="copied ? 'Link Copied!' : 'Admission Link'"></span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Class</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            @if($student->profile_image)
                            <img src="{{ asset($student->profile_image) }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <div class="flex items-center gap-2">
                                {{ $student->name }}
                                @if($student->status == 'pending')
                                <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-200">New</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $student->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $student->schoolClass ? $student->schoolClass->name : 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($student->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                Approved
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                                Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($student->status != 'approved')
                                <a href="{{ route('admin.students.approve', $student->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </a>
                                @endif

                                @if($student->status == 'approved')
                                <a href="{{ route('admin.students.show', $student->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Profile & History">
                                    <i class="fa-solid fa-user"></i>
                                </a>
                                <a href="{{ route('admin.students.fee_card', $student->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="View Fee Card" target="_blank">
                                    <i class="fa-solid fa-receipt"></i>
                                </a>
                                <a href="{{ route('admin.exams.admit-card', $student->id) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View Admit Card" target="_blank">
                                    <i class="fa-solid fa-id-card"></i>
                                </a>
                                @endif
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Update Record">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('admin.students.delete', $student->id) }}" onclick="return confirm('Are you sure you want to delete this student?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $students->links() }}
    </div>
</div>
@endsection