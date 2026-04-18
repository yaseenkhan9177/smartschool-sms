@extends('layouts.accountant')

@section('title', 'Student DMCs - ' . $class->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
                Student DMCs: {{ $class->name }}
            </h1>
            <p class="text-gray-500 mt-1 ml-11">Examination Session: <span class="font-semibold text-purple-600">{{ $activeTerm->name }}</span></p>
        </div>
        <a href="{{ route('accountant.results.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm hover:shadow-md">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to Selection</span>
        </a>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="p-5">Roll No</th>
                        <th class="p-5">Student Name</th>
                        <th class="p-5">Parent Name</th>
                        <th class="p-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-5 font-mono text-gray-500">{{ $student->roll_number ?? 'N/A' }}</td>
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold ring-2 ring-white">
                                    {{ substr($student->name, 0, 2) }}
                                </div>
                                <span class="font-medium text-gray-700">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="p-5 text-gray-500">{{ $student->parent ? $student->parent->father_name : 'N/A' }}</td>
                        <td class="p-5 text-center">
                            <a href="{{ route('accountant.results.print_dmc', ['student_id' => $student->id, 'term_id' => $activeTerm->id]) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-purple-50 border border-purple-200 text-purple-600 hover:text-white hover:bg-purple-600 transition-all shadow-sm" title="Print DMC">
                                <i class="fas fa-print mr-2"></i> Print DMC
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-user-slash text-gray-400"></i>
                                </div>
                                <p>No students found in this class.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-50 bg-gray-50/30">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection