@php
$layout = request()->routeIs('admin.*') ? 'layouts.admin' : 'layouts.accountant';
@endphp

@extends($layout)

@section('title', 'Student Profile & Fee History')

@section('content')
<div class="sm:p-6 p-4">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-4">
            @if($student->profile_picture)
            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Student" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">
            @else
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 text-2xl font-bold border-2 border-indigo-50">
                {{ substr($student->name, 0, 1) }}
            </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Roll Number: <span class="font-medium text-gray-700">{{ $student->roll_number }}</span> | Class: <span class="font-medium text-gray-700">{{ $student->schoolClass->name ?? 'N/A' }}</span></p>
            </div>
        </div>
        <a href="{{ request()->routeIs('admin.*') ? route('admin.students') : route('accountant.students.index') }}" class="text-sm bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium shadow-sm transition flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Students
        </a>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Last 12 Months</p>
                <h3 class="text-xl font-bold text-gray-800">{{ count($invoices) }} Fee Records</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Paid</p>
                <h3 class="text-xl font-bold text-green-600">Rs. {{ number_format($totalPaid, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl mr-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pending / Fine</p>
                <h3 class="text-xl font-bold text-red-600">Rs. {{ number_format($totalPending, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Fee History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-clock-rotate-left mr-2 text-indigo-500"></i> Fee History (Last 12 Months)</h2>
            <a href="{{ request()->routeIs('admin.*') ? route('admin.fees.create') : route('accountant.fees.create') }}?student_id={{ $student->id }}" class="text-xs bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-medium transition flex items-center">
                <i class="fa-solid fa-plus mr-1.5"></i> Add Fee
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 border-collapse">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Month</th>
                        <th class="px-6 py-4 whitespace-nowrap">Invoice No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Due Date</th>
                        <th class="px-6 py-4 whitespace-nowrap">Fee Amount</th>
                        <th class="px-6 py-4 whitespace-nowrap text-red-500">Fine</th>
                        <th class="px-6 py-4 whitespace-nowrap text-green-500">Paid Amount</th>
                        <th class="px-6 py-4 whitespace-nowrap text-orange-500">Balance</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $invoiceNo => $fees)
                    @php
                    // Aggregate data for this invoice
                    $firstFee = $fees->first();
                    $month = \Carbon\Carbon::parse($firstFee->month)->format('M Y');
                    $dueDate = \Carbon\Carbon::parse($firstFee->due_date)->format('d M, Y');

                    $totalAmount = 0;
                    $totalLateFee = 0;
                    $totalDiscount = 0;
                    $totalPaidAmount = 0;
                    $status = 'unpaid';

                    // Determine aggregated status
                    $statuses = $fees->pluck('status')->unique();
                    if ($statuses->contains('partial')) {
                    $status = 'partial';
                    } elseif ($statuses->contains('unpaid') && $statuses->contains('paid')) {
                    $status = 'partial';
                    } elseif ($statuses->contains('unpaid')) {
                    $status = 'unpaid';
                    } else {
                    $status = 'paid';
                    }

                    foreach ($fees as $fee) {
                    $totalAmount += $fee->amount;
                    $totalLateFee += $fee->late_fee;
                    $totalDiscount += $fee->discount;
                    $totalPaidAmount += $fee->payments->sum('amount_paid');
                    }

                    $netPayable = ($totalAmount + $totalLateFee) - $totalDiscount;
                    $balance = max(0, $netPayable - $totalPaidAmount);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $month }}</td>
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $invoiceNo ?: 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if(\Carbon\Carbon::parse($firstFee->due_date)->isPast() && $status !== 'paid')
                            <span class="text-red-500 font-medium">{{ $dueDate }}</span>
                            @else
                            {{ $dueDate }}
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">Rs. {{ number_format($totalAmount, 2) }}</td>
                        <td class="px-6 py-4 text-red-500">Rs. {{ number_format($totalLateFee, 2) }}</td>
                        <td class="px-6 py-4 font-bold text-green-600">Rs. {{ number_format($totalPaidAmount, 2) }}</td>
                        <td class="px-6 py-4 font-bold {{ $balance > 0 ? 'text-orange-500' : 'text-gray-400' }}">Rs. {{ number_format($balance, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($status == 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                            @elseif($status == 'partial')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ request()->routeIs('admin.*') ? route('admin.students.fee_card', $student->id) : route('accountant.students.fee_card', $student->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded" title="Print Fee Card">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm">No fee history found for the last 12 months.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection