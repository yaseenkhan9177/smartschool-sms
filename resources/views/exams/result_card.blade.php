<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Card - {{ $student->name }} - {{ $term->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        body {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>

<body class="bg-gray-100 p-8 min-h-screen">

    <!-- Print Button -->
    <div class="max-w-4xl mx-auto mb-6 no-print flex justify-end">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            Print Result Card
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-12 shadow-lg border border-gray-200">

        <!-- Header -->
        <div class="text-center border-b-2 border-gray-800 pb-6 mb-8">
            <div class="flex justify-center items-center gap-6 mb-4">
                @if($student->school && $student->school->profile_image)
                <img src="{{ asset('storage/' . $student->school->profile_image) }}" alt="School Logo" class="h-24 w-24 object-contain">
                @else
                <div class="h-24 w-24 flex items-center justify-center bg-gray-200 rounded-full text-3xl font-bold text-gray-400">
                    LOGO
                </div>
                @endif

                <div class="text-left">
                    <h1 class="text-4xl font-bold text-gray-900 uppercase tracking-wider font-serif">
                        {{ $student->school->school_name ?? config('app.name') }}
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">Detailed Marks Certificate (DMC)</p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 uppercase mt-4">{{ $term->name }}</h2>
        </div>

        <!-- Student Info -->
        <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-8 text-lg">
            <div class="flex">
                <span class="font-bold w-32">Student Name:</span>
                <span class="border-b border-gray-400 flex-1 px-2 font-serif">{{ $student->name }}</span>
            </div>
            <div class="flex">
                <span class="font-bold w-32">Roll Number:</span>
                <span class="border-b border-gray-400 flex-1 px-2 font-mono font-bold">{{ $student->roll_number ?? 'N/A' }}</span>
            </div>
            <div class="flex">
                <span class="font-bold w-32">Father Name:</span>
                <span class="border-b border-gray-400 flex-1 px-2 font-serif">{{ $student->parent_name ?? '____________' }}</span>
            </div>
            <div class="flex">
                <span class="font-bold w-32">Class:</span>
                <span class="border-b border-gray-400 flex-1 px-2 font-bold">{{ $student->schoolClass->name ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Marks Table -->
        <table class="w-full border-collapse border border-gray-800 mb-8">
            <thead>
                <tr class="bg-gray-100 text-gray-900">
                    <th class="border border-gray-800 p-3 text-left w-1/3">Subject</th>
                    <th class="border border-gray-800 p-3 text-center w-24">Total Marks</th>
                    <th class="border border-gray-800 p-3 text-center w-24">Obtained</th>
                    <th class="border border-gray-800 p-3 text-center w-24">Grade</th>
                    <th class="border border-gray-800 p-3 text-left">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                <tr>
                    <td class="border border-gray-800 p-3 font-medium">{{ $result->subject->name }}</td>
                    <td class="border border-gray-800 p-3 text-center">{{ $result->total_marks }}</td>
                    <td class="border border-gray-800 p-3 text-center font-bold">{{ $result->obtained_marks }}</td>
                    <td class="border border-gray-800 p-3 text-center">{{ $result->grade }}</td>
                    <td class="border border-gray-800 p-3 text-sm italic">{{ $result->remarks }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-gray-800 p-8 text-center text-gray-500 italic">No marks recorded for this term.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-bold text-gray-900 border-t-2 border-gray-800">
                    <td class="border border-gray-800 p-3 text-right">Grand Total</td>
                    <td class="border border-gray-800 p-3 text-center">{{ $totalMax }}</td>
                    <td class="border border-gray-800 p-3 text-center text-xl">{{ $totalObtained }}</td>
                    <td class="border border-gray-800 p-3 text-center">{{ $overallGrade }}</td>
                    <td class="border border-gray-800 p-3">
                        {{ number_format($percentage, 2) }}%
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Grading Policy Reference (Optional) -->
        <div class="mb-12 text-xs text-gray-500">
            * Grading System: A+ (90-100%), A (80-89%), B (70-79%), C (60-69%), D (50-59%), F (Below 50%)
        </div>

        <!-- Signatures -->
        <div class="flex justify-between items-end mt-20 pt-8">
            <div class="text-center w-64">
                <div class="border-b border-gray-800 mb-2"></div>
                <p class="font-bold">Class Teacher Signature</p>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500">Result Generated on: {{ date('d M, Y') }}</p>
            </div>

            <div class="text-center w-64">
                <div class="border-b border-gray-800 mb-2"></div>
                <p class="font-bold">Principal Signature</p>
            </div>
        </div>
    </div>

</body>

</html>