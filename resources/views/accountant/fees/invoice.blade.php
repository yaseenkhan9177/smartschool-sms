<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $fee->id }}</title>
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

    <!-- Print / Download Buttons -->
    <div class="text-center mb-8 no-print space-x-4">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-lg">
            <i class="fa-solid fa-print"></i> Print 3-Part Fee Card
        </button>
        <button onclick="generatePDF()" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors shadow-lg">
            Download PDF
        </button>
    </div>

    <!-- 3 Part Invoice Wrapper -->
    <div id="invoice-content" class="max-w-7xl mx-auto bg-white p-4 print:p-0 rounded-lg shadow-lg print:shadow-none flex flex-row justify-between gap-4">
        @php
        $copies = ['School Copy', 'Bank Copy', 'Student Copy'];

        $grandTotal = 0;
        $totalPaid = 0;
        foreach($fees as $item) {
        $itemTotal = $item->amount + $item->late_fee - $item->discount;
        $grandTotal += $itemTotal;
        $totalPaid += $item->payments->sum('amount_paid');
        }
        $netPayable = $grandTotal - $totalPaid;
        @endphp

        @foreach($copies as $copyName)
        <div class="flex-1 border-2 border-dashed border-gray-300 p-4 relative text-sm print:text-xs">
            <div class="text-center font-bold text-xs print:text-[10px] uppercase bg-gray-100 py-1 mb-4 print:mb-2 border border-gray-200">
                {{ $copyName }}
            </div>

            @if($fee->status == 'paid')
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -rotate-12 border-4 border-green-500 text-green-500 text-center opacity-30 rounded-lg px-4 py-2 pointer-events-none z-0">
                <span class="text-4xl font-black block">PAID</span>
                <span class="text-sm font-bold block">{{ \Carbon\Carbon::parse($fee->updated_at)->format('d M, y') }}</span>
            </div>
            @endif

            <!-- Header -->
            <div class="text-center border-b border-gray-200 pb-2 mb-2 relative z-10">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="h-10 w-auto object-contain mx-auto mb-1">
                <h2 class="text-lg font-bold text-purple-600 leading-tight">{{ $fee->student->school->school_name ?? 'School Name' }}</h2>
                <p class="text-[10px] text-gray-500">{{ $fee->student->school->address ?? 'Address' }} | {{ $fee->student->school->phone ?? '' }}</p>
                <h1 class="text-md font-bold text-gray-800 mt-2">FEE CHALLAN</h1>
                <p class="text-xs text-gray-500">#INV-{{ str_pad($fee->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- Student Info -->
            <div class="grid grid-cols-2 gap-2 mb-4 text-[11px] print:text-[10px]">
                <div>
                    <p class="text-gray-500 font-bold">Student Name:</p>
                    <p class="font-bold text-gray-800">{{ $fee->student->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 font-bold">Roll No:</p>
                    <p class="font-bold text-gray-800">{{ $fee->student->roll_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold">Father Name:</p>
                    <p class="font-bold text-gray-800">{{ $fee->student->parent->name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 font-bold">Class:</p>
                    <p class="font-bold text-gray-800">{{ $fee->student->schoolClass->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold">Issue Date:</p>
                    <p class="font-bold text-gray-800">{{ date('d M, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 font-bold">Due Date:</p>
                    <p class="font-bold text-red-600">{{ \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') }}</p>
                </div>
            </div>

            <!-- Table -->
            <table class="w-full mb-4 text-[11px] print:text-[10px] border border-gray-200">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-2 py-1 text-left font-bold text-gray-700">Fee Details</th>
                        <th class="px-2 py-1 text-right font-bold text-gray-700">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($fees as $item)
                    <tr>
                        <td class="px-2 py-1">
                            {{ $item->feeStructure->feeCategory->name }}
                            <span class="text-gray-500 text-[9px] block">
                                {{ $item->month === 'Remaining Balance' ? 'Remaining Balance' : \Carbon\Carbon::parse($item->month)->format('M y') }}
                            </span>
                        </td>
                        <td class="px-2 py-1 text-right font-bold text-gray-800">
                            {{ number_format($item->amount, 2) }}
                        </td>
                    </tr>
                    @if($item->late_fee > 0)
                    <tr>
                        <td class="px-2 py-1 italic text-red-500">Late Fee</td>
                        <td class="px-2 py-1 text-right font-bold text-red-500">{{ number_format($item->late_fee, 2) }}</td>
                    </tr>
                    @endif
                    @if($item->discount > 0)
                    <tr>
                        <td class="px-2 py-1 italic text-green-500">Discount</td>
                        <td class="px-2 py-1 text-right font-bold text-green-500">-{{ number_format($item->discount, 2) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td class="px-2 py-1.5 text-right font-bold text-gray-800 border-t border-gray-200">Total</td>
                        <td class="px-2 py-1.5 text-right font-bold text-gray-800 border-t border-gray-200">{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                    @if($totalPaid > 0)
                    <tr>
                        <td class="px-2 py-1 text-right font-medium text-green-600">Paid</td>
                        <td class="px-2 py-1 text-right font-medium text-green-600">-{{ number_format($totalPaid, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="px-2 py-2 text-right text-sm print:text-xs font-black text-purple-700 border-t border-gray-200">Payable</td>
                        <td class="px-2 py-2 text-right text-sm print:text-xs font-black text-purple-700 border-t border-gray-200">
                            Rs. {{ number_format($netPayable, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Signatures -->
            <div class="grid grid-cols-2 gap-4 mt-8 pt-4 border-t border-gray-200 text-center text-[10px] print:text-[9px]">
                <div>
                    <div class="border-b border-gray-400 mx-4 mb-1 h-6"></div>
                    <p class="text-gray-500">Cashier Signature</p>
                </div>
                <div>
                    <div class="border-b border-gray-400 mx-4 mb-1 h-6"></div>
                    <p class="text-gray-500">Depositor Signature</p>
                </div>
            </div>

            <p class="text-center text-[9px] text-gray-400 mt-4">System Generated Fee Challan</p>
        </div>
        @endforeach
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
                filename: 'invoice-{{ $fee->id }}.pdf',
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

        // Auto-trigger if ?download=true
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('download')) {
                generatePDF();
            }
        };
    </script>
</body>

</html>