@php
$layout = request()->routeIs('admin.*') ? 'layouts.admin' : 'layouts.accountant';
@endphp

@extends($layout)

@section('title', 'Comprehensive Fee Reports')

@section('content')
<div class="sm:p-6 p-4">
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Fee Reports Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor fee collections, pendings, and defaulters.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Expected</h3>
                    <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($totalAmount) }}</p>
                </div>
                <div class="p-3 bg-blue-50 bg-opacity-50 rounded-lg">
                    <i class="fa-solid fa-file-invoice-dollar text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4"><i class="fa-solid fa-clock mr-1"></i> Based on filters</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-semibold text-green-600 uppercase tracking-wider mb-1">Total Collected</h3>
                    <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($totalPaid) }}</p>
                </div>
                <div class="p-3 bg-green-50 bg-opacity-50 rounded-lg">
                    <i class="fa-solid fa-hand-holding-dollar text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4">
                @php $collectedPercent = $totalAmount > 0 ? ($totalPaid / $totalAmount) * 100 : 0; @endphp
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, $collectedPercent) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ number_format($collectedPercent, 1) }}% Collection Rate</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-semibold text-orange-500 uppercase tracking-wider mb-1">Pending Balance</h3>
                    <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($totalPending) }}</p>
                </div>
                <div class="p-3 bg-orange-50 bg-opacity-50 rounded-lg">
                    <i class="fa-solid fa-clock-rotate-left text-orange-500 text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4">
                @php $pendingPercent = $totalAmount > 0 ? ($totalPending / $totalAmount) * 100 : 0; @endphp
                <div class="bg-orange-400 h-1.5 rounded-full" style="width: {{ min(100, $pendingPercent) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ number_format($pendingPercent, 1) }}% Uncollected</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full -mr-4 -mt-4 z-0"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <h3 class="text-sm font-semibold text-red-600 uppercase tracking-wider mb-1">Defaulter Invoices</h3>
                    <p class="text-2xl font-bold text-red-700">{{ $defaultersCount }}</p>
                </div>
                <div class="p-3 bg-red-100 bg-opacity-50 rounded-lg">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-red-500 mt-4 font-medium"><i class="fa-solid fa-arrow-up-right-dots mr-1"></i> Over 30 days overdue</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-50 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-filter text-indigo-500 mr-2"></i> Report Filters</h2>
            <a href="{{ url()->current() }}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium">Clear All</a>
        </div>
        <div class="p-5">
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Class</label>
                    <select name="class_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Unpaid+Partial)</option>
                        <option value="defaulter" {{ request('status') == 'defaulter' ? 'selected' : '' }}>Defaulters (> 30 Days)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Student Name/Roll No..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                </div>
                <div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-list text-gray-500 mr-2"></i> Report Data</h2>
            <button onclick="window.print()" class="text-sm bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium shadow-sm transition">
                <i class="fa-solid fa-print mr-1"></i> Print Report
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fee Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Expected</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($reports as $fee)
                    @php
                    $feeTotal = $fee->amount + $fee->late_fee - $fee->discount;
                    $feePaid = $fee->payments->sum('amount_paid');

                    $isDefaulter = in_array($fee->status, ['unpaid', 'partial']) && \Carbon\Carbon::parse($fee->due_date)->diffInDays(now()) > 30;
                    @endphp
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                    {{ substr($fee->student->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $fee->student->name }}</div>
                                    <div class="text-xs text-gray-500">Roll: {{ $fee->student->roll_number ?? 'N/A' }} | Class: {{ $fee->student->schoolClass->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $fee->feeStructure->feeCategory->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($fee->month)->format('F Y') }} | Inv: {{ $fee->invoice_no ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $isDefaulter ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                {{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}
                            </div>
                            @if($isDefaulter)
                            <div class="text-xs text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>Defaulter</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-800">
                            {{ number_format($feeTotal) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                            {{ number_format($feePaid) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($fee->status == 'paid')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                Paid
                            </span>
                            @elseif($fee->status == 'partial')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Partial
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                Unpaid
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                <p class="text-base font-medium text-gray-600">No fee records found for the selected criteria</p>
                                <p class="text-sm mt-1">Try adjusting your filters or search query.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @media print {
        body {
            padding: 0 !important;
            margin: 0 !important;
        }

        .sm\:p-6,
        .p-4 {
            padding: 0 !important;
        }

        .no-print,
        form,
        nav,
        header,
        aside,
        .mb-6 {
            display: none !important;
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, 1fr) !important;
        }

        .shadow-sm,
        .shadow-lg {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }

        .bg-white {
            background: #fff !important;
        }

        /* Make sure cards colors appear */
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection