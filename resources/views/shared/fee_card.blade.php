@extends($layout)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Student Fee Card</h2>
            <p class="text-sm text-gray-500 mt-1">12-Month Fee History for {{ $student->name }} ({{ $student->roll_no }})</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 shadow-lg flex items-center gap-2 print:hidden">
            <i class="fa-solid fa-print"></i> Print Fee Card
        </button>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white rounded-2xl p-6 mb-6 shadow-sm border border-gray-100 flex items-center gap-6">
        @if($student->profile_image)
        <img src="{{ asset('uploads/students/' . $student->profile_image) }}" alt="{{ $student->name }}" class="w-20 h-20 rounded-full object-cover border-4 border-indigo-50">
        @else
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-2xl border-4 border-indigo-50">
            {{ substr($student->name, 0, 1) }}
        </div>
        @endif

        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Student Name</p>
                <p class="font-semibold text-gray-900">{{ $student->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Class</p>
                <p class="font-semibold text-gray-900">{{ $student->schoolClass->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Father's Name</p>
                <p class="font-semibold text-gray-900">{{ $student->father_name ?? $student->parent->father_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Roll Number</p>
                <p class="font-semibold text-gray-900">{{ $student->roll_no }}</p>
            </div>
        </div>
    </div>

    <!-- Fee Card Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Month</th>
                        <th class="px-6 py-4 text-right">Fee Amount</th>
                        <th class="px-6 py-4 text-right">Late Fine</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Payment Date</th>
                        <th class="px-6 py-4">Receipt Number</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($feeCards as $card)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $card->month }}
                            <p class="text-xs text-slate-400 font-normal mt-0.5">{{ $card->invoice_no }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-medium text-gray-800">Rs. {{ number_format($card->total_amount) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-red-500 font-medium">Rs. {{ number_format($card->late_fine) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($card->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fa-solid fa-check mr-1.5"></i> Paid
                            </span>
                            @elseif($card->status === 'partial')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Partial
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fa-solid fa-xmark mr-1.5"></i> Unpaid
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($card->payment_date)
                            {{ $card->payment_date }}
                            @else
                            <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            @if($card->receipt_no !== 'N/A')
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded">{{ $card->receipt_no }}</span>
                            @else
                            <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="font-medium">No fee records found for the last 12 months.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection