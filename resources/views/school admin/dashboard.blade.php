@extends('layouts.admin')

@section('content')
<!-- Quick Actions & Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-500 mt-1">Welcome back, here's what's happening today.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.fees.collect.index') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all flex items-center">
            <i class="fa-solid fa-hand-holding-dollar mr-2"></i> Collect Fee
        </a>
        <a href="{{ route('admin.students') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex items-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Add Student
        </a>
        <a href="{{ route('admin.expenses.create') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition-all flex items-center">
            <i class="fa-solid fa-receipt mr-2 text-gray-400"></i> Add Expense
        </a>
        <a href="{{ route('admin.teacher-attendance.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all flex items-center">
            <i class="fa-solid fa-user-check mr-2"></i> Attendance
        </a>
        <button onclick="alert('SMS/WhatsApp Broadcast Feature Coming Soon!')" class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 shadow-lg shadow-green-500/20 transition-all flex items-center">
            <i class="fa-brands fa-whatsapp mr-2"></i> SMS/WhatsApp
        </button>
    </div>
</div>

<!-- Financial Snapshot Widget (Crucial) -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Critical Alert: Student Reports -->
    <a href="{{ route('admin.reports.index') }}" class="block">
        <div class="rounded-2xl p-6 relative overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 shadow-md group {{ $pendingReportsCount > 0 ? 'bg-red-600 text-white shadow-red-500/30' : 'bg-white border border-gray-200' }}">
            <div class="absolute top-0 right-0 w-24 h-24 {{ $pendingReportsCount > 0 ? 'bg-white/10' : 'bg-red-50' }} rounded-full -mr-8 -mt-8 blur-sm"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-bold text-sm uppercase tracking-wider {{ $pendingReportsCount > 0 ? 'text-red-100' : 'text-gray-500' }}">Student Reports</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $pendingReportsCount > 0 ? 'bg-white/20 text-white animate-pulse' : 'bg-red-50 text-red-500' }}">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold mb-1 {{ $pendingReportsCount > 0 ? 'text-white' : 'text-gray-900' }}">{{ $pendingReportsCount }}</h3>
                <p class="text-xs font-medium flex items-center {{ $pendingReportsCount > 0 ? 'text-red-100' : 'text-red-500' }}">
                    {{ $pendingReportsCount > 0 ? 'Action Required!' : 'All Clear' }} <i class="fa-solid fa-arrow-right ml-1"></i>
                </p>
            </div>
        </div>
    </a>
    <!-- Today's Collection -->
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-emerald-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-xl"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <p class="text-emerald-100 font-medium text-sm uppercase tracking-wider">Today's Collection</p>
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-coins text-white text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">PKR {{ number_format($todaysCollection ?? 0, 2) }}</h3>
            <p class="text-xs text-emerald-100">Processed today</p>
        </div>
    </div>

    <!-- Monthly Collection -->
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-xl"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <p class="text-blue-100 font-medium text-sm uppercase tracking-wider">Monthly Collection</p>
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-chart-line text-white text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">PKR {{ number_format($monthlyCollection ?? 0, 2) }}</h3>
            <p class="text-xs text-blue-100">This Month</p>
        </div>
    </div>

    <!-- Pending Fees (Clickable) -->
    <a href="{{ route('admin.fees.collect.index') }}?filter=defaulters" class="block">
        <div class="bg-white rounded-2xl p-6 border border-red-100 relative overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 shadow-sm hover:shadow-md cursor-pointer group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-full -mr-8 -mt-8"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-gray-500 font-bold text-sm uppercase tracking-wider group-hover:text-red-600 transition-colors">Total Pending</p>
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-100 transition-colors">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">PKR {{ number_format($totalPendingFees ?? 0, 2) }}</h3>
                <p class="text-xs font-bold text-red-500 flex items-center">
                    Click to View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                </p>
            </div>
        </div>
    </a>
</div>

<!-- Stats Grid (Original) -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Students -->
    <a href="{{ route('admin.students') }}" class="block">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-blue-300 transition-colors group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <span class="flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> Active
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalStudents ?? '0' }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Students</p>
            </div>
        </div>
    </a>

    <!-- Card 2: Teachers -->
    <a href="{{ route('admin.teachers') }}" class="block">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-emerald-300 transition-colors group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <span class="flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> Active
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalTeachers ?? '0' }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Teachers</p>
            </div>
        </div>
    </a>



    <!-- Card 4: Expenses (Replaced Classes) -->
    <a href="{{ route('admin.expenses.index') }}" class="block">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-red-300 transition-colors group relative overflow-hidden">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <!-- Tooltip trigger -->
                <div class="relative flex flex-col items-center group/tooltip cursor-help">
                    <span class="flex items-center text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-full">
                        <i class="fa-solid fa-circle-exclamation mr-1.5"></i> Total
                    </span>
                    <!-- Tooltip for Monthly Expenses -->
                    <div class="absolute bottom-full mb-2 w-max px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-300 z-10 shadow-xl">
                        This Month: PKR {{ number_format($monthlyExpenses ?? '0') }}
                        <!-- Arrow -->
                        <div class="absolute top-full left-1/2 -ml-1 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">PKR {{ number_format($totalExpenses ?? '0') }}</h3>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Expenses Overview</p>
                <p class="text-[10px] text-gray-400 mt-1 group-hover:text-red-500 transition-colors">Hover "Total" for monthly</p>
            </div>
        </div>
    </a>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    <!-- Chart Section (Spans 2 cols) -->
    <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-800">Financial Overview</h3>
            <select id="chartFilter" class="text-xs border-gray-200 rounded-lg text-gray-500 focus:ring-blue-500 cursor-pointer">
                <option value="year">Last 12 Months</option>
                <option value="month">This Month</option>
            </select>
        </div>
        <div class="h-80 w-full">
            <canvas id="financialChart"></canvas>
        </div>
    </div>

    <!-- Live Attendance Widget -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-800">Live Attendance</h3>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Today</span>
        </div>

        <div class="flex items-center justify-around">
            <!-- Staff (Real Data) -->
            <div class="text-center">
                <div class="relative w-24 h-24 mx-auto mb-3">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-gray-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                        <path class="text-blue-500" stroke-dasharray="{{ $totalTeachers > 0 ? round(($totalTeachersPresent / $totalTeachers) * 100) : 0 }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                    </svg>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                        <span class="text-xl font-bold text-gray-900">{{ $totalTeachers > 0 ? round(($totalTeachersPresent / $totalTeachers) * 100) : 0 }}%</span>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-600">Staff</p>
                <p class="text-xs text-red-500 mt-1">{{ $totalTeachersAbsent }} Absent</p>
            </div>

            <!-- Students -->
            <div class="text-center">
                <div class="relative w-24 h-24 mx-auto mb-3">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-gray-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                        <path class="text-emerald-500" stroke-dasharray="{{ $totalStudents > 0 ? round(($totalStudentsPresent / $totalStudents) * 100) : 0 }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                    </svg>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                        <span class="text-xl font-bold text-gray-900">{{ $totalStudents > 0 ? round(($totalStudentsPresent / $totalStudents) * 100) : 0 }}%</span>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-600">Students</p>
                <p class="text-xs text-red-500 mt-1">{{ $totalStudentsAbsent }} Absent</p>
            </div>
        </div>
    </div>
</div>

<!-- Tables Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Live School Activity -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Live School Activity</h3>
            <span class="text-xs text-gray-400 font-medium">Real-time Feed</span>
        </div>
        <div class="p-0">
            <ul class="divide-y divide-gray-50">
                @forelse($recentActivities as $activity)
                <li class="p-4 hover:bg-gray-50/50 transition-colors flex items-start gap-4">
                    @php
                    $iconClass = 'fa-circle-info';
                    $bgClass = 'bg-gray-100';
                    $textClass = 'text-gray-600';

                    switch($activity->action_type) {
                    case 'fee':
                    $iconClass = 'fa-sack-dollar';
                    $bgClass = 'bg-emerald-100';
                    $textClass = 'text-emerald-600';
                    break;
                    case 'attendance':
                    $iconClass = 'fa-calendar-check';
                    $bgClass = 'bg-blue-100';
                    $textClass = 'text-blue-600';
                    break;
                    case 'result':
                    $iconClass = 'fa-graduation-cap';
                    $bgClass = 'bg-purple-100';
                    $textClass = 'text-purple-600';
                    break;
                    case 'alert':
                    $iconClass = 'fa-bell';
                    $bgClass = 'bg-red-100';
                    $textClass = 'text-red-600';
                    break;
                    }
                    @endphp
                    <div class="w-10 h-10 rounded-full {{ $bgClass }} {{ $textClass }} flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $iconClass }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity->message }}</p>
                        <p class="text-xs text-gray-400 mt-1 flex items-center">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                </li>
                @empty
                <li class="p-8 text-center text-gray-500">
                    <i class="fa-solid fa-hotel text-gray-300 text-4xl mb-3 block"></i>
                    No recent activity found.
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Fee Defaulters List -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-red-600"><i class="fa-solid fa-circle-exclamation mr-2"></i>Fee Defaulters</h3>
            <a href="{{ route('admin.fees.collect.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-red-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Student</th>
                        <th class="px-6 py-4 font-semibold">Amount</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($defaulters as $fee)
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs">
                                    {{ substr($fee->student->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $fee->student->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $fee->student->schoolClass->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-red-600">PKR {{ number_format($fee->amount, 0) }}</span>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($fee->due_date)->format('d M') }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <a href="{{ route('admin.fees.collect.index') }}" class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                Collect
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-green-500 font-medium">
                            <i class="fa-solid fa-check-circle mr-2"></i> No pending fees!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">📅 Upcoming Events</h3>
            <a href="{{ route('admin.events.create') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">+ New</a>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($upcomingEvents as $event)
                <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors border border-dashed border-gray-200">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex flex-col items-center justify-center shrink-0 border border-indigo-100">
                        <span class="text-xs font-bold uppercase">{{ $event->event_date->format('M') }}</span>
                        <span class="text-lg font-bold">{{ $event->event_date->format('d') }}</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $event->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $event->description }}</p>
                        <span class="text-xs text-gray-400 mt-1 block"><i class="fa-regular fa-clock mr-1"></i> {{ $event->event_date->format('h:i A') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-calendar-xmark text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500">No events scheduled.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Financial Chart
        const ctxFinance = document.getElementById('financialChart').getContext('2d');
        const months = <?php echo json_encode($months); ?>;
        const incomeData = <?php echo json_encode($incomeData); ?>;
        const expenseData = <?php echo json_encode($expenseData); ?>;

        let financialChart = new Chart(ctxFinance, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                        label: 'Income',
                        data: incomeData,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Expenses',
                        data: expenseData,
                        borderColor: '#ef4444', // red-500
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        // Chart Filter Logic
        const filterSelect = document.getElementById('chartFilter');
        filterSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'month') {
                financialChart.data.labels = <?php echo json_encode($currentMonthDays ?? []); ?>;
                financialChart.data.datasets[0].data = <?php echo json_encode($dailyIncomeData ?? []); ?>;
                financialChart.data.datasets[1].data = <?php echo json_encode($dailyExpenseData ?? []); ?>;
            } else {
                financialChart.data.labels = months;
                financialChart.data.datasets[0].data = incomeData;
                financialChart.data.datasets[1].data = expenseData;
            }
            financialChart.update();
        });
    });
</script>
@endsection