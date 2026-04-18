@extends('layouts.accountant')

@section('title', 'Admit Cards - ' . $class->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                    <i class="fa-solid fa-id-card-clip"></i>
                </span>
                Admit Cards: {{ $class->name }}
            </h1>
            <p class="text-gray-500 mt-1 ml-11">Examination Session: <span class="font-semibold text-purple-600">{{ $activeTerm->name }}</span></p>
        </div>
        <a href="{{ route('accountant.exams.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm hover:shadow-md">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to Classes</span>
        </a>
    </div>

    <!-- Print Actions Toolbar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Batch Print Options</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Print All -->
            <form action="{{ route('accountant.exams.print_batch') }}" method="POST" target="_blank" class="block h-full">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="term_id" value="{{ $activeTerm->id }}">
                <input type="hidden" name="type" value="all">
                <button type="submit" class="w-full h-full flex items-center justify-between p-4 bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-xl hover:shadow-lg hover:shadow-gray-500/30 transition-all group border border-transparent hover:scale-[1.01]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center text-xl group-hover:bg-white/20 transition-colors">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Print All</span>
                            <span class="text-xs text-gray-300">Entire Class Batch</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-500 group-hover:text-white transition-colors"></i>
                </button>
            </form>

            <!-- Print Paid Only -->
            <form action="{{ route('accountant.exams.print_batch') }}" method="POST" target="_blank" class="block h-full">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="term_id" value="{{ $activeTerm->id }}">
                <input type="hidden" name="type" value="paid">
                <button type="submit" class="w-full h-full flex items-center justify-between p-4 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl hover:shadow-lg hover:shadow-green-500/30 transition-all group border border-transparent hover:scale-[1.01]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center text-xl group-hover:bg-white/20 transition-colors">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Paid Only</span>
                            <span class="text-xs text-green-100">Cleared Dues</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-green-200 group-hover:text-white transition-colors"></i>
                </button>
            </form>

            <!-- Print Unpaid Only -->
            <form action="{{ route('accountant.exams.print_batch') }}" method="POST" target="_blank" class="block h-full">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="term_id" value="{{ $activeTerm->id }}">
                <input type="hidden" name="type" value="unpaid">
                <button type="submit" class="w-full h-full flex items-center justify-between p-4 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/30 transition-all group border border-transparent hover:scale-[1.01]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center text-xl group-hover:bg-white/20 transition-colors">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="text-left">
                            <span class="block font-bold">Unpaid Only</span>
                            <span class="text-xs text-red-100">Pending Dues</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-red-200 group-hover:text-white transition-colors"></i>
                </button>
            </form>
        </div>
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
                        <th class="p-5">Fee Status</th>
                        <th class="p-5">Pending Balance</th>
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
                        <td class="p-5">
                            @if($student->pending_balance > 0)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Unpaid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Paid
                            </span>
                            @endif
                        </td>
                        <td class="p-5">
                            @if($student->pending_balance > 0)
                            <span class="font-bold text-red-600">Rs {{ number_format($student->pending_balance) }}</span>
                            @else
                            <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="p-5 text-center">
                            <a href="{{ route('accountant.exams.view_slip', $student->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-purple-600 hover:border-purple-200 hover:bg-purple-50 transition-all shadow-sm" title="View Admit Card">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
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