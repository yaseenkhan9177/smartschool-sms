<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Invoices</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page-break {
                page-break-after: always;
            }

            .no-print {
                display: none;
            }
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
</head>

<body class="bg-gray-100 p-8">

    @foreach($fees as $fee)
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg mb-8 page-break">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8 relative">
            @if($fee->status == 'paid')
            <div class="absolute pt-12 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -rotate-12 border-4 border-green-500 text-green-500 text-6xl font-black opacity-50 rounded-lg px-4 py-2 pointer-events-none z-0">
                PAID
            </div>
            @endif
            <div class="relative z-10">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="h-16 w-auto object-contain mb-4">
                <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                <p class="text-gray-500 mt-1">#INV-{{ str_pad($fee->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right relative z-10">
                <h2 class="text-xl font-bold text-purple-600">AstriaLearning</h2>
                <p class="text-sm text-gray-500 mt-1">123 School Street</p>
                <p class="text-sm text-gray-500">Education City, ED 12345</p>
                <p class="text-sm text-gray-500">contact@astria.com</p>
            </div>
        </div>

        <!-- Info -->
        <div class="flex justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bill To</p>
                <p class="font-bold text-gray-800">{{ $fee->student->name }}</p>
                <p class="text-sm text-gray-500">{{ $fee->student->email }}</p>
                <p class="text-sm text-gray-500">Class: {{ $fee->student->schoolClass->name ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                <p class="font-bold text-gray-800">{{ date('M d, Y') }}</p>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-4 mb-1">Due Date</p>
                <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-4 py-4 text-sm text-gray-800">
                        <p class="font-medium">{{ $fee->feeStructure->feeCategory->name }}</p>
                        <p class="text-xs text-gray-500 font-medium">
                            @if($fee->month === 'Remaining Balance')
                            Remaining Balance
                            @else
                            Fee for {{ $fee->month }}
                            @endif
                        </p>
                    </td>
                    <td class="px-4 py-4 text-right text-sm font-bold text-gray-800">PKR {{ number_format($fee->amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="px-4 py-4 text-right text-sm font-bold text-gray-800 border-t border-gray-200">Total</td>
                    <td class="px-4 py-4 text-right text-sm font-bold text-gray-800 border-t border-gray-200">PKR {{ number_format($fee->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-right text-sm font-medium text-green-600">Paid</td>
                    <td class="px-4 py-2 text-right text-sm font-medium text-green-600">-PKR {{ number_format($fee->payments->sum('amount_paid'), 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-right text-lg font-bold text-purple-600 border-t border-gray-200">Balance Due</td>
                    <td class="px-4 py-4 text-right text-lg font-bold text-purple-600 border-t border-gray-200">
                        PKR {{ number_format($fee->amount - $fee->payments->sum('amount_paid'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Payment History -->
        @if($fee->payments->count() > 0)
        <div class="mb-8">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Payment History</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                @foreach($fee->payments as $payment)
                <div class="flex justify-between text-sm mb-2 last:mb-0">
                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }} ({{ ucfirst($payment->payment_method) }})</span>
                    <span class="font-medium text-gray-800">PKR {{ number_format($payment->amount_paid, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="border-t border-gray-200 pt-8 text-center text-gray-500 text-sm">
            <p>Thank you for choosing AstriaLearning.</p>
        </div>
    </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>