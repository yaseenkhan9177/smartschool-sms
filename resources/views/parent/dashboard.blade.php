<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#f4f6f9] font-[Inter] antialiased min-h-screen flex flex-col" x-data="{ showTeachersModal: false, showLeaveModal: false, showComplaintModal: false }">

    <!-- LAYER 1: The "Hero" Identity Card -->
    <!-- Visual Style: Wide, colorful card (Gradient Blue/Purple) -->
    <header class="hero-gradient text-white pt-8 pb-16 px-4 sm:px-6 lg:px-8 shadow-xl relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">

            @if($currentStudent)
            <div class="flex items-center gap-6 w-full md:w-auto">
                <!-- Left Side: Large circular Profile Photo -->
                <div class="relative items-center justify-center flex">
                    @if($currentStudent->profile_image)
                    <img src="{{ asset('uploads/students/' . $currentStudent->profile_image) }}" class="w-24 h-24 rounded-full border-4 border-white/20 shadow-lg object-cover">
                    @else
                    <div class="w-24 h-24 rounded-full bg-white/10 border-4 border-white/20 flex items-center justify-center text-3xl font-bold shadow-lg">
                        {{ substr($currentStudent->name, 0, 1) }}
                    </div>
                    @endif
                    <!-- Green "Active" dot -->
                    <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-400 border-2 border-indigo-600 rounded-full" title="Active"></span>
                </div>

                <!-- Middle: Big Name & Details -->
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold tracking-tight">{{ $currentStudent->name }}</h1>
                    <p class="text-indigo-100 text-lg mt-1 font-medium">Class {{ $currentStudent->schoolClass->name ?? 'N/A' }} | Roll No: {{ $currentStudent->roll_number }}</p>
                    <p class="text-indigo-200 text-sm mt-1 opacity-80 uppercase tracking-widest text-[10px]">Admission No: ADM-{{ date('Y') }}-{{ $currentStudent->id }}</p>
                </div>
            </div>

            <!-- Right Side: "Switch Child" and "Log Out" -->
            <div class="flex items-center gap-3">
                @if($students->count() > 1)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10">
                        <span class="text-sm font-medium">Switch Child</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl overflow-hidden py-1 z-50 text-gray-800" x-cloak>
                        @foreach($students as $s)
                        <a href="{{ route('parent.dashboard', ['student_id' => $s->id]) }}" class="block px-4 py-3 hover:bg-gray-50 {{ $s->id === $currentStudent->id ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                            {{ $s->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ route('logout') }}" class="bg-white/10 hover:bg-red-500/80 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10" title="Logout">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
            @else
            <div class="text-center w-full">
                <h1 class="text-2xl font-bold">No Students Linked</h1>
                <p>Please contact the school admin.</p>
                <a href="{{ route('logout') }}" class="inline-block mt-4 bg-white/20 px-4 py-2 rounded-lg">Logout</a>
            </div>
            @endif

        </div>
    </header>

    @if($currentStudent)
    <!-- Main Content Container with greater spacing -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 flex-1 w-full pb-12 pt-4">

        <!-- LAYER 2: The "Vital Stats" Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

            <!-- Card A: Attendance (Live) -->
            <a href="{{ route('parent.attendance', ['student_id' => $currentStudent->id]) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-transform block">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <!-- Today's Badge -->
                    @php
                    // Hacky check for today's attendance
                    $todayStatus = $currentStudent->attendances->where('attendance_date', date('Y-m-d'))->first();
                    @endphp
                    @if($todayStatus && $todayStatus->status == 'present')
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide">Present Today</span>
                    @elseif($todayStatus && $todayStatus->status == 'absent')
                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide">Absent Today</span>
                    @else
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide">No Status</span>
                    @endif
                </div>

                @php
                $present = $currentStudent->attendances->where('status', 'present')->count();
                $absent = $currentStudent->attendances->where('status', 'absent')->count();
                $totalAtt = $currentStudent->attendances->count();
                $rate = $totalAtt > 0 ? round(($present / $totalAtt) * 100) : 0;

                if ($totalAtt == 0) {
                $rateDisplay = 'N/A';
                $color = 'text-gray-400';
                $barColor = 'bg-gray-200';
                } else {
                $rateDisplay = $rate . '%';
                $color = $rate >= 75 ? 'text-green-600' : 'text-red-600';
                $barColor = $rate >= 75 ? 'bg-green-500' : 'bg-red-500';
                }
                @endphp

                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Attendance</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold {{ $color }}">{{ $rateDisplay }}</h3>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between text-xs text-gray-500">
                    <span>Present: <strong class="text-gray-900">{{ $present }}</strong></span>
                    <span>Absent: <strong class="text-gray-900">{{ $absent }}</strong></span>
                </div>
                <!-- Visual Progress Bar (Bottom Line) -->
                <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
                    <div class="h-full {{ $barColor }}" style="width: <?php echo ($totalAtt > 0 ? $rate : 0); ?>%"></div>
                </div>
            </a>

            <!-- Card B: Fee Status (Financial) -->
            <a href="{{ route('parent.fees', ['student_id' => $currentStudent->id]) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-transform block">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div class="bg-gray-50 text-gray-400 p-1 rounded-full px-2 text-[10px] font-bold uppercase hover:bg-gray-100 transition">View History</div>
                </div>

                @php
                $pending = $currentStudent->studentFees->where('status', '!=', 'paid')->sum('amount');
                $paid = $currentStudent->studentFees->where('status', 'paid')->sum('amount');
                $totalFees = $currentStudent->studentFees->sum('amount');
                @endphp

                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Fee Status</p>
                <div class="flex items-baseline gap-2">
                    @if($pending > 0)
                    <h3 class="text-3xl font-bold text-gray-900">Rs {{ number_format($pending) }}</h3>
                    <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded">Partial</span>
                    @else
                    <h3 class="text-3xl font-bold text-green-600">Cleared</h3>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-0.5 rounded">All Paid</span>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between text-xs text-gray-500 items-center">
                    <span>Paid: <strong>{{ number_format($paid) }}</strong> / {{ number_format($totalFees) }}</span>
                    <!-- "Pay Now" logic removed here as it is better suited in the Fees History page or as a button that doesn't conflict with current wrapper -->
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
                    <div class="h-full {{ $pending > 0 ? 'bg-orange-500' : 'bg-green-500' }}" style="width: <?php echo ($pending > 0 ? '40' : '100'); ?>%"></div>
                </div>
            </a>

            <!-- Card C: Recent Performance (Academic) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                </div>

                @php
                $lastResult = $currentStudent->examResults->last();
                $grade = $lastResult ? $lastResult->grade : 'N/A';
                $marks = $lastResult ? $lastResult->obtained_marks . '/' . $lastResult->total_marks : '-';
                @endphp

                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Recent Performance</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">Grade: {{ $grade }}</h3>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between text-xs text-gray-500">
                    <span>Marks: <strong class="text-gray-900">{{ $marks }}</strong></span>
                    <span>Term: {{ $lastResult->examTerm->name ?? 'None' }}</span>
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-gray-100 w-full">
                    <div class="h-full bg-purple-500" style="width: 85%"></div>
                </div>
            </div>

        </div>

        <!-- LAYER 3: The "Action Center" -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <!-- Left Column: The Daily Work (Tabs) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'homework' }">
                <!-- Tab Headers -->
                <div class="flex border-b border-gray-100">
                    <button @click="tab = 'homework'" :class="tab === 'homework' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-4 text-sm font-semibold border-b-2 transition-colors">
                        Homework
                    </button>
                    <button @click="tab = 'timetable'" :class="tab === 'timetable' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-4 text-sm font-semibold border-b-2 transition-colors">
                        Timetable
                    </button>
                    <button @click="tab = 'exams'" :class="tab === 'exams' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-4 text-sm font-semibold border-b-2 transition-colors">
                        Exam Results
                    </button>
                    <button @click="tab = 'complaints'" :class="{ 'bg-indigo-600 text-white shadow-lg shadow-indigo-200': tab === 'complaints', 'bg-white text-gray-600 hover:bg-gray-50': tab !== 'complaints' }" class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-all border border-transparent" :class="{ 'border-gray-100': tab !== 'complaints' }">
                        Complaints
                    </button>
                    <button @click="tab = 'behavior'" :class="{ 'bg-indigo-600 text-white shadow-lg shadow-indigo-200': tab === 'behavior', 'bg-white text-gray-600 hover:bg-gray-50': tab !== 'behavior' }" class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-all border border-transparent" :class="{ 'border-gray-100': tab !== 'behavior' }">
                        Warnings
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-6 min-h-[300px]">
                    <!-- Tab 1: Homework -->
                    <div x-show="tab === 'homework'" x-transition.opacity>
                        <!-- Real Data List View -->
                        <div class="space-y-4">
                            @forelse($homeworks as $homework)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                        {{ substr($homework->subject->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $homework->subject->name ?? 'Subject' }} - {{ $homework->title }}</h4>
                                        <p class="text-xs text-gray-500">Assigned: {{ $homework->assigned_date->format('M d') }} • Due: {{ $homework->due_date->format('M d, Y') }}</p>
                                        @if($homework->description)
                                        <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $homework->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                @php
                                $isOverdue = $homework->due_date->isPast();
                                @endphp
                                @if($isOverdue)
                                <span class="text-xs font-bold bg-gray-200 text-gray-600 px-3 py-1 rounded-full">Passed</span>
                                @else
                                <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">Pending</span>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No homework assigned.</p>
                                <p class="text-xs text-gray-400">Great job!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 2: Timetable -->
                    <div x-show="tab === 'timetable'" x-transition.opacity x-cloak>
                        <!-- Timeline View -->
                        <div class="relative space-y-6 pl-4 border-l-2 border-indigo-100 ml-2">
                            @forelse($currentStudent->schoolClass->timetables as $slot)
                            <div class="relative">
                                <span class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-indigo-500 border-2 border-white ring-2 ring-indigo-100"></span>
                                <h4 class="text-sm font-bold text-gray-900">{{ date('h:i A', strtotime($slot->start_time)) }} - {{ $slot->subject->name ?? 'Subject' }}</h4>
                                <p class="text-xs text-gray-500">{{ $slot->teacher->name ?? 'Teacher' }} • {{ ucfirst($slot->day) }}</p>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-6">No timetable entries found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 3: Exam Results -->
                    <div x-show="tab === 'exams'" x-transition.opacity x-cloak>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-4">Subject</th>
                                        <th class="py-3 px-4">Term</th>
                                        <th class="py-3 px-4">Marks</th>
                                        <th class="py-3 px-4">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($currentStudent->examResults as $result)
                                    <tr>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $result->subject->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-gray-500">{{ $result->examTerm->name ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $result->obtained_marks }}/{{ $result->total_marks }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">{{ $result->grade }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 text-right">
                                <button class="text-indigo-600 text-sm font-medium hover:text-indigo-800">
                                    <i class="fa-solid fa-download mr-1"></i> Download Result Card
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Complaints -->
                    <div x-show="tab === 'complaints'" x-transition.opacity x-cloak>
                        <div class="space-y-4">
                            @forelse($complaints as $complaint)
                            <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $complaint->subject }}</h4>
                                        <p class="text-xs text-gray-400">{{ $complaint->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $complaint->status == 'resolved' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $complaint->description }}</p>
                                @if($complaint->admin_response)
                                <div class="mt-3 pl-3 border-l-2 border-indigo-200">
                                    <p class="text-xs text-indigo-600 font-bold mb-1">Admin Response:</p>
                                    <p class="text-sm text-gray-700">{{ $complaint->admin_response }}</p>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-check-circle text-4xl mb-3 text-gray-200"></i>
                                <p>No complaints filed.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 5: Behavior/Warnings -->
                    <div x-show="tab === 'behavior'" x-transition.opacity x-cloak>
                        <div class="space-y-4">
                            @forelse($studentReports as $report)
                            <div class="p-4 rounded-xl border-l-4 {{ $report->severity == 'high' ? 'border-red-500 bg-red-50' : ($report->severity == 'medium' ? 'border-orange-500 bg-orange-50' : 'border-yellow-500 bg-yellow-50') }} shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-triangle-exclamation {{ $report->severity == 'high' ? 'text-red-500' : ($report->severity == 'medium' ? 'text-orange-500' : 'text-yellow-500') }}"></i>
                                        <h4 class="font-bold text-gray-900">Variables/Behavior Alert</h4>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500">{{ $report->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-sm font-bold text-gray-800 mb-1">Student: {{ $report->student->name }}</p>
                                <p class="text-sm text-gray-700">{{ $report->reason }}</p>

                                @if($report->resolution_note)
                                <div class="mt-3 pl-3 border-l-2 border-gray-300">
                                    <p class="text-xs text-gray-600 font-bold mb-1">Admin Note:</p>
                                    <p class="text-sm text-gray-700">{{ $report->resolution_note }}</p>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-star text-4xl mb-3 text-yellow-200"></i>
                                <p>No behavior reports. Excellent!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Notice Board -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-orange-500"></i> Notice Board
                </h3>
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($notices as $notice)
                    <div class="bg-orange-50/50 p-3 rounded-xl border border-orange-100">
                        <div class="flex justify-between items-start">
                            <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $notice->title }}</h4>
                            <i class="fa-solid fa-bell text-orange-400 text-xs"></i>
                        </div>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $notice->description }}</p>
                        <p class="text-[10px] text-gray-400 mt-2">{{ $notice->event_date->format('M d, Y') }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No notices at the moment.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- LAYER 4: Communication & Downloads -->
        <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Teacher Section -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                @php
                // Get first teacher found
                $teacher = $currentStudent->schoolClass->teachers->first();
                @endphp
                @if($teacher)
                <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=random" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $teacher->name }}</p>
                    <p class="text-xs text-gray-500">Class Teacher</p>
                    <button @click="showTeachersModal = true" class="text-[10px] text-indigo-600 font-bold hover:underline mt-0.5">
                        View All Subject Teachers
                    </button>
                </div>
                <!-- Removed Message Button to reduce clutter or keep if needed -->
                <!-- <button class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100" title="Message Teacher">
                    <i class="fa-regular fa-comment-dots"></i>
                </button> -->
                @else
                <div class="p-2 text-xs text-gray-400">No Teacher Assigned</div>
                @endif
            </div>

            <!-- Apply for Leave -->
            <button @click="showLeaveModal = true" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:border-indigo-200 transition text-left group">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-person-walking-luggage"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Apply for Leave</p>
                    <p class="text-xs text-gray-500">Sick, Urgent, etc.</p>
                </div>
            </button>

            <!-- Message Admin -->
            <button @click="showComplaintModal = true" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:border-indigo-200 transition text-left group">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Contact Admin</p>
                    <p class="text-xs text-gray-500">Direct query or complaint</p>
                </div>
            </button>

            <!-- Downloads Area -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-around gap-2">
                <a href="#" class="flex flex-col items-center gap-1 text-center group">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-100 transition">
                        <i class="fa-regular fa-file-pdf"></i>
                    </div>
                    <span class="text-[10px] font-medium text-gray-600">Report</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-1 text-center group">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-100 transition">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <span class="text-[10px] font-medium text-gray-600">Receipts</span>
                </a>
                <a href="{{ route('parent.exams', ['student_id' => $currentStudent->id]) }}" class="flex flex-col items-center gap-1 text-center group">
                    <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:bg-yellow-100 transition">
                        <i class="fa-regular fa-calendar-alt"></i>
                    </div>
                    <span class="text-[10px] font-medium text-gray-600">Exam Schedule</span>
                </a>
            </div>

            <!-- Quick Links -->
            <div class="gap-2 flex flex-col justify-center">
                <a href="#" class="flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 transition">
                    <i class="fa-solid fa-arrow-right text-xs"></i> View Attendance History
                </a>
                <a href="#" class="flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 transition">
                    <i class="fa-solid fa-arrow-right text-xs"></i> Apply for Leave
                </a>
            </div>

        </div>

    </main>
    @endif

    <footer class="mt-auto py-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Own Education Systems. All rights reserved.
    </footer>

    @if($currentStudent)
    <!-- Subject Teachers Modal -->
    <div x-show="showTeachersModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showTeachersModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up transition-all transform">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Subject Teachers</h3>
                    <button @click="showTeachersModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <!-- Content same as before -->
                <div class="space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    @php
                    $uniqueTeachers = collect();
                    if($currentStudent && $currentStudent->schoolClass) {
                    $uniqueTeachers = $currentStudent->schoolClass->timetables->unique('subject_id');
                    }
                    @endphp
                    @forelse($uniqueTeachers as $slot)
                    <div class="flex items-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm mr-3 shrink-0">
                            {{ substr($slot->subject->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $slot->subject->name ?? 'Subject' }}</p>
                            <p class="text-xs text-gray-500">{{ $slot->teacher->name ?? 'Not Assigned' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">No subject teachers found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- LEAVE APPLICATION MODAL -->
    <div x-show="showLeaveModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showLeaveModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-fade-in-up transition-all transform">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Apply for Leave</h3>
                    <button @click="showLeaveModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('parent.leave.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $currentStudent->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason Type</label>
                        <select name="type" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2.5 border">
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Urgent Work">Urgent Work</option>
                            <option value="Marriage Ceremony">Marriage Ceremony</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="from_date" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="to_date" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2 border">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="reason" rows="3" placeholder="e.g. He has a high fever..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2 border"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- COMPLAINT MODAL -->
    <div x-show="showComplaintModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-cloak>
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showComplaintModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-fade-in-up transition-all transform">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Contact Admin/Principal</h3>
                    <button @click="showComplaintModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('parent.complaint.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" name="subject" required placeholder="Regarding..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2.5 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" required rows="4" placeholder="Type your query or complaint here..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2 border"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> <i class="fa-solid fa-times"></i>
    </button>
    </div>
    <form action="{{ route('parent.complaint.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input type="text" name="subject" required placeholder="Regarding..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2.5 border">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea name="message" required rows="4" placeholder="Type your query or complaint here..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition shadow-sm p-2 border"></textarea>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Send Message
            </button>
        </div>
    </form>
    </div>
    </div>
    </div>

</body>

</html>