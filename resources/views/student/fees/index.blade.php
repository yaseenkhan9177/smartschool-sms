@extends('layouts.student')

@section('header', 'My Fees')

@section('content')
<div class="grid gap-6">
    <!-- Fee Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Payable ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-white">
                    PKR {{ number_format($totalPayableYear, 2) }}
                </h3>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Paid ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-white">
                    PKR {{ number_format($totalPaidYear, 2) }}
                </h3>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Outstanding</p>
                <h3 class="text-2xl font-bold text-white">
                    PKR {{ number_format($totalOutstanding, 2) }}
                </h3>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Next Due Date</p>
                <h3 class="text-2xl font-bold text-white">
                    {{ $nextDueDate ? \Carbon\Carbon::parse($nextDueDate)->format('M d, Y') : 'No Dues' }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Fee List -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Invoice History</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-300">
                <thead class="bg-gray-800/50 text-xs uppercase font-medium text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Invoice No</th>
                        <th class="px-6 py-4">Month</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 text-right">Net Payable</th>
                        <th class="px-6 py-4 text-right">Paid</th>
                        <th class="px-6 py-4 text-right">Balance</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-800/30 transition-colors cursor-pointer group" onclick="document.getElementById('details-{{ $invoice->invoice_no }}').classList.toggle('hidden')">
                        <td class="px-6 py-4 font-medium text-white flex items-center gap-2">
                            <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-white transition-colors"></i>
                            {{ $invoice->invoice_no ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->month === 'Remaining Balance')
                            Remaining Balance
                            @else
                            {{ \Carbon\Carbon::parse($invoice->month)->format('F Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 {{ $invoice->status == 'overdue' ? 'text-red-400 font-bold' : '' }}">
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-white">PKR {{ number_format($invoice->net_payable, 2) }}</td>
                        <td class="px-6 py-4 text-right text-purple-300">PKR {{ number_format($invoice->total_paid, 2) }}</td>
                        <td class="px-6 py-4 text-right text-red-300">PKR {{ number_format($invoice->balance, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($invoice->status == 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20"> Paid </span>
                            @elseif($invoice->status == 'partial')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20"> Partially Paid </span>
                            @elseif($invoice->status == 'overdue')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20"> Overdue </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20"> Unpaid </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            <!-- View Details (Eye Icon) -->
                            <button onclick="event.stopPropagation(); document.getElementById('details-{{ $invoice->invoice_no }}').classList.toggle('hidden')" class="text-xs bg-gray-700 hover:bg-gray-600 text-gray-300 p-2 rounded-lg transition-colors" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <!-- Download Receipt -->
                            <a href="{{ route('student.fees.invoice', $invoice->id) }}" target="_blank" onclick="event.stopPropagation()" class="text-xs bg-purple-500/20 text-purple-300 p-2 rounded-lg hover:bg-purple-500/30 transition-colors" title="Download Receipt">
                                <i class="fa-solid fa-download"></i>
                            </a>

                            <!-- Pay Now (Placeholder for now) -->
                            @if($invoice->status != 'paid')
                            <button onclick="event.stopPropagation(); alert('Online payment integration coming soon!')" class="text-xs bg-green-500/20 text-green-400 px-3 py-2 rounded-lg hover:bg-green-500/30 transition-colors flex items-center gap-1" title="Pay Online">
                                <i class="fa-regular fa-credit-card"></i> Pay
                            </button>
                            @endif
                        </td>
                    </tr>
                    <!-- Expanded Details Row -->
                    <tr id="details-{{ $invoice->invoice_no }}" class="hidden bg-gray-800/20">
                        <td colspan="8" class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Fee Breakdown -->
                                <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                                    <h4 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider">Fee Breakdown</h4>
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-gray-500 border-b border-gray-700">
                                                <th class="pb-2 text-left">Fee Category</th>
                                                <th class="pb-2 text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700/50">
                                            @foreach($invoice->items as $item)
                                            <tr>
                                                <td class="py-2 text-gray-300">
                                                    {{ $item->feeStructure->feeCategory->name }}
                                                    @if($item->late_fee > 0) <span class="text-xs text-red-400 block">(Inc. Late Fee)</span> @endif
                                                    @if($item->discount > 0) <span class="text-xs text-green-400 block">(Inc. Discount)</span> @endif
                                                </td>
                                                <td class="py-2 text-right font-bold text-white">{{ number_format($item->amount + $item->late_fee - $item->discount, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Payment History for this Invoice -->
                                <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                                    <h4 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider">Payment History</h4>
                                    @php
                                    $invoicePayments = collect();
                                    if(isset($invoice->items)) {
                                    $invoicePayments = $invoice->items->flatMap(function($item) {
                                    return $item->payments ?? collect();
                                    })->sortByDesc('payment_date');
                                    }
                                    @endphp

                                    @if($invoicePayments->count() > 0)
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-gray-500 border-b border-gray-700">
                                                <th class="pb-2 text-left">Date</th>
                                                <th class="pb-2 text-center">Method</th>
                                                <th class="pb-2 text-right">Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700/50">
                                            @foreach($invoicePayments as $payment)
                                            <tr>
                                                <td class="py-2 text-gray-300">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                                <td class="py-2 text-center text-xs uppercase text-gray-500">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                                <td class="py-2 text-right font-bold text-green-400">{{ number_format($payment->amount_paid, 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p class="text-gray-500 text-sm italic py-2">No payments made for this invoice yet.</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-receipt text-4xl mb-3 opacity-50"></i>
                                <p>No fee records found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Transactions -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50">
            <h3 class="text-xl font-bold text-white">Payment Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-300">
                <thead class="bg-gray-800/50 text-xs uppercase font-medium text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Fee</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4 text-right">Amount Paid</th>
                        <th class="px-6 py-4">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ $payment->studentFee->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 uppercase text-xs tracking-wider">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                        <td class="px-6 py-4 text-right text-green-400 font-bold">PKR {{ number_format($payment->amount_paid, 2) }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $payment->remarks ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No payment transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection