@extends('layouts.accountant')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Financial Reports</h1>
            <p class="text-gray-500 text-sm mt-1">Detailed analysis of fee collection and outstanding balances.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Collection Trend -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Monthly Collection Trend</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-100">
                            <th class="py-3 font-medium">Month</th>
                            <th class="py-3 font-medium text-right">Collected Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($monthlyCollection as $record)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="py-3 text-gray-800 font-medium">{{ \Carbon\Carbon::createFromDate($record->year, $record->month, 1)->format('F Y') }}</td>
                            <td class="py-3 text-right text-purple-600 font-bold">PKR {{ number_format($record->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-500">No collection data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Defaulters -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Outstanding Balances</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-100">
                            <th class="py-3 font-medium">Student</th>
                            <th class="py-3 font-medium">Class</th>
                            <th class="py-3 font-medium text-right">Pending Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($topDefaulters as $defaulter)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="py-3 text-gray-800 font-medium">{{ $defaulter['student_name'] }}</td>
                            <td class="py-3 text-gray-500">{{ $defaulter['class_name'] }}</td>
                            <td class="py-3 text-right text-red-600 font-bold">PKR {{ number_format($defaulter['total_pending'], 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">No outstanding balances found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection