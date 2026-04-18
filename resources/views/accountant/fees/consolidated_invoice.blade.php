<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Invoice - {{ $student->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
</head>

<body class="bg-gray-100 p-8">

    <div id="invoice-content" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="h-16 w-auto object-contain mb-4">
                <h1 class="text-3xl font-bold text-gray-800">CONSOLIDATED INVOICE</h1>
                <p class="text-gray-500 mt-1">Student ID: {{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
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
                <p class="font-bold text-gray-800">{{ $student->name }}</p>
                <p class="text-sm text-gray-500">{{ $student->email }}</p>
                <p class="text-sm text-gray-500">Class: {{ $student->schoolClass->name ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                <p class="font-bold text-gray-800">{{ date('M d, Y') }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fee Description</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Month</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Paid</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $totalDue = 0; @endphp
                @forelse($fees as $fee)
                @php
                $paid = $fee->payments->sum('amount_paid');
                $balance = $fee->amount - $paid;
                $totalDue += $balance;
                @endphp
                <tr>
                    <td class="px-4 py-4 text-sm text-gray-800">
                        {{ $fee->feeStructure->feeCategory->name }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $fee->month }}
                    </td>
                    <td class="px-4 py-4 text-right text-sm text-gray-800">PKR {{ number_format($fee->amount, 2) }}</td>
                    <td class="px-4 py-4 text-right text-sm text-green-600">PKR {{ number_format($paid, 2) }}</td>
                    <td class="px-4 py-4 text-right text-sm font-bold text-gray-800">PKR {{ number_format($balance, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No outstanding fees found.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="px-4 py-4 text-right text-lg font-bold text-purple-600 border-t border-gray-200">Total Balance Due</td>
                    <td class="px-4 py-4 text-right text-lg font-bold text-purple-600 border-t border-gray-200">
                        PKR {{ number_format($totalDue, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-8 text-center">
            <p class="text-sm text-gray-500">Thank you for your business!</p>
            <p class="text-xs text-gray-400 mt-2">This is a computer generated invoice and does not require a signature.</p>
        </div>
    </div>

    <div class="text-center mt-8 no-print space-x-4">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-lg">
            Print Invoice
        </button>
        <button onclick="generatePDF()" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors shadow-lg">
            Download PDF
        </button>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('invoice-content');
            const opt = {
                margin: 1,
                filename: 'consolidated-invoice-{{ $student->id }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>

</body>

</html>