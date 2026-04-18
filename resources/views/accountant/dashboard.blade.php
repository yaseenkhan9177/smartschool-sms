@extends('layouts.accountant')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $schoolName }} - Dashboard Overview</h1>
            <p class="text-gray-500 text-sm mt-1">Welcome back, {{ session('accountant_name') }}</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fa-solid fa-download mr-2"></i>Export Report
            </button>
            <a href="{{ route('accountant.fees.collect.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>Collect Fee
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 p-6 rounded-2xl shadow-lg shadow-purple-500/20 text-white relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-sack-dollar text-lg"></i>
                    </div>
                    <span class="text-xs font-medium bg-white/20 backdrop-blur-sm px-2 py-1 rounded-lg">Total</span>
                </div>
                <h3 class="text-purple-100 text-sm font-medium">Total Revenue</h3>
                <p class="text-2xl font-bold mt-1">PKR {{ number_format($totalRevenue, 2) }}</p>
                <div class="mt-2 flex items-center text-xs font-medium {{ $revenueGrowth >= 0 ? 'text-green-300' : 'text-red-300' }}">
                    <i class="fa-solid {{ $revenueGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    <span>{{ number_format(abs($revenueGrowth), 1) }}% vs Last Month</span>
                </div>
            </div>
        </div>

        <!-- Pending Fees -->
        <a href="{{ route('accountant.fees.collect.index', ['status' => 'pending']) }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md hover:border-orange-200 group block">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clock text-lg"></i>
                </div>
                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Action Needed</span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Pending</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">PKR {{ number_format($pendingFees, 2) }}</p>
            <div class="mt-2 text-xs font-medium text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fa-solid fa-arrow-right mr-1"></i>View all pending
            </div>
        </a>

        <!-- Expenses -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm card-hover group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-lg">Expenses</span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Expenses</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">PKR {{ number_format($totalExpenses, 2) }}</p>
        </div>

        <!-- Today's Collection -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm card-hover group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-money-bill-wave text-lg"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-lg">Today</span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Today's Collection</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">PKR {{ number_format($todaysCollection, 2) }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-800">Revenue Analytics ({{ $chartViewType }})</h2>
                <select id="chartFilter" class="text-sm border-gray-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-gray-500 cursor-pointer">
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"
                    data-months='@json($months)'
                    data-revenue='@json($revenueData)'
                    data-last-year-revenue='@json($lastYearRevenueData)'
                    data-expenses='@json($expenseData)'
                    data-daily-months='@json($currentMonthDays)'
                    data-daily-revenue='@json($dailyIncomeData)'
                    data-daily-expenses='@json($dailyExpenseData)'>
                </canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Recent Activity</h2>
            <div class="space-y-6">
                @forelse($recentActivity as $activity)
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-50 flex items-center justify-center text-{{ $activity['color'] }}-600 shrink-0">
                        <i class="fa-solid {{ $activity['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $activity['subtitle'] }}</p>
                        <p class="text-xs font-bold text-{{ $activity['color'] }}-600 mt-1">
                            {{ $activity['type'] == 'income' ? '+' : '-' }}PKR {{ number_format($activity['amount'], 2) }}
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 ml-auto">{{ $activity['time'] }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No recent activity.</p>
                @endforelse
            </div>
            <a href="{{ route('accountant.fees.collect.index') }}" class="block w-full text-center mt-6 py-2 text-sm text-purple-600 font-medium hover:bg-purple-50 rounded-xl transition-colors">
                View All Activity
            </a>
        </div>

        <!-- Upcoming Staff Meetings -->
        @if(isset($upcomingMeetings) && $upcomingMeetings->count() > 0)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mt-6 lg:mt-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Staff Meetings</h2>
            <div class="space-y-4">
                @foreach($upcomingMeetings as $meeting)
                <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0 border border-blue-100">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ $meeting->topic }}</h4>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $meeting->start_time->format('d M, h:i A') }}
                            @if($meeting->status == 'started')
                            <span class="text-green-600 font-bold ml-1">(Live)</span>
                            @endif
                        </p>
                        <a href="{{ route('meetings.join', $meeting->id) }}" target="_blank" class="inline-block mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors font-medium">Join Meeting</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upcoming Events -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mt-6 lg:mt-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Upcoming Events</h2>
            <div class="space-y-4">
                @forelse($upcomingEvents as $event)
                <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex flex-col items-center justify-center shrink-0 border border-purple-100">
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
                    <p class="text-sm text-gray-500">No upcoming events</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const canvas = document.getElementById('revenueChart');
    const chartData = {
        months: JSON.parse(canvas.dataset.months),
        revenue: JSON.parse(canvas.dataset.revenue),
        lastYearRevenue: JSON.parse(canvas.dataset.lastYearRevenue),
        expenses: JSON.parse(canvas.dataset.expenses),
        dailyMonths: JSON.parse(canvas.dataset.dailyMonths),
        dailyRevenue: JSON.parse(canvas.dataset.dailyRevenue),
        dailyExpenses: JSON.parse(canvas.dataset.dailyExpenses)
    };

    const ctx = canvas.getContext('2d');
    let revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.dailyMonths,
            datasets: [{
                label: 'Revenue (This Month)',
                data: chartData.dailyRevenue,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Expenses',
                data: chartData.dailyExpenses,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                tension: 0.4,
                fill: true,
                borderDash: [2, 2]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart Filter Logic
    const filterSelect = document.getElementById('chartFilter');
    filterSelect.addEventListener('change', function() {
        const value = this.value;
        if (value === 'year') {
            revenueChart.data.labels = chartData.months;
            revenueChart.data.datasets = [{
                label: 'Revenue (This Year)',
                data: chartData.revenue,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Revenue (Last Year)',
                data: chartData.lastYearRevenue,
                borderColor: '#94a3b8',
                backgroundColor: 'rgba(148, 163, 184, 0.05)',
                tension: 0.4,
                fill: false,
                borderDash: [5, 5]
            }, {
                label: 'Expenses',
                data: chartData.expenses,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                tension: 0.4,
                fill: true,
                borderDash: [2, 2]
            }];
        } else {
            revenueChart.data.labels = chartData.dailyMonths;
            revenueChart.data.datasets = [{
                label: 'Revenue (This Month)',
                data: chartData.dailyRevenue,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Expenses',
                data: chartData.dailyExpenses,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                tension: 0.4,
                fill: true,
                borderDash: [2, 2]
            }];
        }
        revenueChart.update();
    });
</script>
@endsection