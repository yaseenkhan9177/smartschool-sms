<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal | Attendance</title>
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

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }
    </style>
</head>

<body class="bg-gray-50 font-[Inter] antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="hero-gradient text-white pt-8 pb-16 px-4 sm:px-6 lg:px-8 shadow-xl relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">

            @if($currentStudent)
            <div class="flex items-center gap-6 w-full md:w-auto">
                <a href="{{ route('parent.dashboard', ['student_id' => $currentStudent->id]) }}" class="text-white/80 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold tracking-tight">Attendance Record</h1>
                    <p class="text-indigo-100 text-lg mt-1 font-medium">{{ $currentStudent->name }}</p>
                </div>
            </div>

            <!-- Right Side -->
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
                        <a href="{{ route('parent.attendance', ['student_id' => $s->id]) }}" class="block px-4 py-3 hover:bg-gray-50 {{ $s->id === $currentStudent->id ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
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
            @endif

        </div>
    </header>

    @if($currentStudent)
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 flex-1 w-full pb-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <!-- Summary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs text-green-600 font-bold uppercase">Present</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $currentStudent->attendances->where('status', 'present')->count() }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-xs text-red-600 font-bold uppercase">Absent</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $currentStudent->attendances->where('status', 'absent')->count() }}</p>
                </div>
                <!-- Add more stats if needed -->
            </div>

            <!-- Simple List View for now as full calendar logic is complex in Blade alone without a package -->
            <!-- Using a month-by-month grouped list -->
            <h3 class="text-lg font-bold text-gray-900 mb-4">Daily Attendance Log</h3>

            <div class="space-y-6">
                @php
                $grouped = $currentStudent->attendances->sortByDesc('attendance_date')->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->attendance_date)->format('F Y');
                });
                @endphp

                @forelse($grouped as $month => $records)
                <div class="border rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b font-bold text-gray-700">
                        {{ $month }}
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($records as $att)
                        <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($att->attendance_date)->format('D, d M') }}
                            </span>

                            @if($att->status == 'present')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Present
                            </span>
                            @elseif($att->status == 'absent')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Absent
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($att->status) }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No attendance records found.</p>
                @endforelse
            </div>
        </div>
    </main>
    @endif

    <footer class="mt-auto py-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Own Education Systems. All rights reserved.
    </footer>

</body>

</html>