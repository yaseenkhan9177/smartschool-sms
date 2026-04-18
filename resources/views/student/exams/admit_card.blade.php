<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule & Admit Card - {{ $student->name }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .print-container {
                box-shadow: none;
                border: 2px solid #000;
                width: 100%;
                max-width: 100%;
                padding: 20px;
                margin: 0;
            }
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            color: rgba(226, 232, 240, 0.4);
            z-index: 0;
            pointer-events: none;
            font-weight: bold;
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
</head>

<body class="bg-gray-100 min-h-screen p-8 flex flex-col items-center justify-start pt-24 print:p-0 print:bg-white">

    <!-- Print Button -->
    <div class="fixed top-6 right-6 no-print space-x-4">
        <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition-colors">
            <i class="fas fa-print mr-2"></i> Print Schedule / Admit Card
        </button>
    </div>

    <!-- Admit Card Container -->
    <div class="print-container w-full max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden relative print:rounded-none">
        <!-- Watermark -->
        <div class="watermark no-print">ADMIT CARD</div>

        <!-- Header -->
        <div class="bg-blue-900 text-white p-6 md:p-8 flex justify-between items-center print:bg-white print:text-black print:border-b-2 print:border-black">
            <div class="flex items-center space-x-6">
                <!-- Logo Placehoder -->
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-blue-900 font-bold text-2xl print:border-2 print:border-black">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold uppercase tracking-wider">Own Education System</h1>
                    <p class="text-blue-200 mt-1 print:text-black">Excellence in Education</p>
                    <p class="text-xs text-blue-300 mt-1 print:text-gray-600">123 School Road, City, Country</p>
                </div>
            </div>
            <div class="text-right hidden md:block print:block">
                <div class="text-sm opacity-75 print:text-black">Examination Session</div>
                <div class="text-2xl font-bold print:text-black">{{ $activeTerm->name }}</div>
            </div>
        </div>

        <!-- Student Info Section -->
        <div class="p-8 relative z-10">
            <div class="flex flex-col md:flex-row gap-8 items-start border-b-2 border-gray-100 pb-8 mb-8 print:border-black">
                <!-- Student Photo -->
                <div class="w-32 h-32 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden border-2 border-gray-300 print:border-black">
                    @if($student->profile_image)
                    <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Student Photo" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-user text-4xl"></i>
                    </div>
                    @endif
                </div>

                <!-- Details Grid -->
                <div class="flex-1 grid grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-1 print:text-black">Student Name</span>
                        <span class="text-lg font-bold text-gray-800 print:text-black">{{ $student->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-1 print:text-black">Roll Number</span>
                        <span class="text-lg font-bold text-blue-800 print:text-black">{{ $student->roll_number ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-1 print:text-black">Class</span>
                        <span class="text-lg font-bold text-gray-800 print:text-black">{{ $student->schoolClass->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-1 print:text-black">Parent's Name</span>
                        <span class="text-lg font-bold text-gray-800 print:text-black">{{ $student->parent_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- QR Code Stub -->
                <div class="hidden md:block print:block">
                    <div class="w-24 h-24 bg-white border border-gray-200 p-2 flex items-center justify-center print:border-black">
                        <!-- Placeholder QR -->
                        <i class="fas fa-qrcode text-4xl text-gray-800"></i>
                    </div>
                </div>
            </div>

            <!-- Exam Countdown (Screen Only) -->
            @php
            $nextExam = $schedules->filter(function($exam) {
            return \Carbon\Carbon::parse($exam->exam_date->format('Y-m-d') . ' ' . $exam->start_time)->isFuture();
            })->first();
            @endphp

            @if($nextExam)
            <div class="mb-8 p-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg text-white text-center no-print transform hover:scale-[1.01] transition-transform">
                <h4 class="text-xl font-bold uppercase tracking-wider mb-2">Next Exam: {{ $nextExam->subject->name }}</h4>
                <div class="text-3xl font-mono font-bold" id="countdown">Loading...</div>
                <p class="text-sm text-blue-100 mt-2">{{ $nextExam->exam_date->format('l, d M Y') }} at {{ \Carbon\Carbon::parse($nextExam->start_time)->format('h:i A') }}</p>
                <script>
                    // Simple Countdown
                    const targetDate = new Date("{{ $nextExam->exam_date->format('Y-m-d') }}T{{ $nextExam->start_time }}").getTime();

                    setInterval(function() {
                        const now = new Date().getTime();
                        const distance = targetDate - now;

                        if (distance < 0) {
                            document.getElementById("countdown").innerHTML = "EXAM STARTED";
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        // const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        document.getElementById("countdown").innerHTML = `${days}d ${hours}h ${minutes}m`;
                    }, 1000);
                </script>
            </div>
            @endif

            <!-- Exam Schedule Table -->
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center print:text-black">
                <i class="far fa-calendar-alt mr-2 text-blue-600 print:hidden"></i> Exam Schedule
            </h3>

            <div class="overflow-hidden rounded-lg border border-gray-200 print:border-black shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 print:divide-black">
                    <thead class="bg-gray-50 print:bg-white print:border-b print:border-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider print:text-black print:border-r print:border-black">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider print:text-black print:border-r print:border-black">Subject / Paper</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider print:text-black print:border-r print:border-black">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider print:text-black">Room</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 print:divide-black text-sm">
                        @forelse($schedules as $schedule)
                        @php
                        $isToday = $schedule->exam_date->isToday();
                        $isPast = $schedule->exam_date->isPast() && !$isToday;
                        $rowClass = $isToday ? 'bg-yellow-50 ring-1 ring-yellow-200' : ($isPast ? 'bg-gray-50 opacity-75' : 'hover:bg-blue-50');
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors print:bg-white">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 print:border-r print:border-black">
                                {{ $schedule->exam_date->format('d M, Y') }} <br>
                                <span class="text-xs text-gray-500 print:text-black">{{ $schedule->exam_date->format('l') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap print:border-r print:border-black">
                                <div class="font-bold text-gray-800">{{ $schedule->subject->name ?? 'Subject' }}</div>
                                <div class="text-xs text-blue-600 font-semibold uppercase tracking-wider mt-1">{{ $schedule->paper_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 print:border-r print:border-black">
                                <div class="font-mono">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                <span class="font-bold">{{ $schedule->room ?? 'TBA' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                                No exam schedule released yet for this term.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Instructions -->
            <div class="mt-8 pt-6 border-t border-gray-100 print:border-black">
                <h4 class="text-sm font-bold text-gray-700 mb-2 uppercase print:text-black">Important Instructions:</h4>
                <ul class="text-xs text-gray-600 list-disc pl-5 space-y-1 print:text-black">
                    <li>This admit card is mandatory for entry into the examination hall.</li>
                    <li>Students must report 15 minutes before the scheduled time.</li>
                    <li>Use of electronic gadgets is strictly prohibited inside the exam hall.</li>
                    <li>Maintain silence and discipline during the examination.</li>
                </ul>
            </div>

            <div class="mt-12 flex justify-between items-end print:mt-16">
                <div class="text-center">
                    <div class="w-32 border-b border-black mb-2"></div>
                    <p class="text-xs font-bold">Principal's Signature</p>
                </div>
                <div class="text-center">
                    <div class="w-32 border-b border-black mb-2"></div>
                    <p class="text-xs font-bold">Controller of Exams</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>