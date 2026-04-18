<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Card - {{ $student->first_name }} {{ $student->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-slate-100 py-10 px-4">

    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6 no-print">
            <a href="/" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
                &larr; Back to Home
            </a>
        </div>

        <!-- Student Info Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200 mb-8 p-8 relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <img src="{{ asset('assets/img/logo.jpg') }}" class="w-24 h-24 rounded-full">
            </div>

            <div class="flex items-center gap-6 relative z-10">
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold border-4 border-blue-50">
                    {{ substr($student->first_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <div class="flex gap-4 mt-2 text-slate-500 font-medium">
                        <span>Roll No: <span class="text-slate-900">{{ $student->roll_number }}</span></span>
                        <span>&bull;</span>
                        <span>Class: <span class="text-slate-900">{{ $student->schoolClass->name ?? 'N/A' }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results by Term -->
        @forelse($results as $termId => $termResults)
        @php $term = $termResults->first()->term; @endphp
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200 mb-8">
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800">{{ $term->name }}</h2>
                <span class="text-sm text-slate-500">{{ $termResults->count() }} Subjects</span>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subject</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Marks Obtained</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                    $totalObtained = 0;
                    $grandTotal = 0;
                    @endphp
                    @foreach($termResults as $result)
                    @php
                    $totalObtained += $result->obtained_marks;
                    $grandTotal += $result->total_marks;
                    @endphp
                    @php
                    $gradeClass = 'bg-red-100 text-red-700';
                    if($result->grade == 'A' || $result->grade == 'A+') $gradeClass = 'bg-green-100 text-green-700';
                    elseif($result->grade == 'B') $gradeClass = 'bg-blue-100 text-blue-700';
                    elseif($result->grade == 'C') $gradeClass = 'bg-yellow-100 text-yellow-700';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-4 font-medium text-slate-700">{{ $result->subject->name }}</td>
                        <td class="px-8 py-4 font-bold text-slate-900 text-right">{{ $result->obtained_marks }}</td>
                        <td class="px-8 py-4 text-slate-500 text-right">{{ $result->total_marks }}</td>
                        <td class="px-8 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $gradeClass }}">
                                {{ $result->grade }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td class="px-8 py-4 font-bold text-slate-800">Total</td>
                        <td class="px-8 py-4 font-bold text-blue-600 text-right text-lg">{{ $totalObtained }}</td>
                        <td class="px-8 py-4 font-bold text-slate-600 text-right text-lg">{{ $grandTotal }}</td>
                        <td class="px-8 py-4 text-center font-bold text-slate-500">
                            {{ $grandTotal > 0 ? round(($totalObtained / $grandTotal) * 100, 1) . '%' : '-' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @empty
        <div class="text-center py-12">
            <p class="text-slate-500">No exam results found for this student.</p>
        </div>
        @endforelse

        <div class="text-center mt-8 no-print">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold shadow-lg hover:bg-blue-700 transition-colors">
                Print Result Card
            </button>
        </div>
    </div>

</body>

</html>