@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.fees.collect.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Collect Fee</h1>
            <p class="text-gray-500 text-sm mt-1">Record payment for {{ $fee->student->name }}</p>
        </div>
    </div>

    <!-- Fee Details Card -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-gray-50 rounded-xl">
            <p class="text-sm text-gray-500 mb-1">Base Amount</p>
            <p class="text-lg font-bold text-gray-900">PKR {{ number_format($fee->amount, 2) }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl text-right">
            <p class="text-sm text-gray-500 mb-1">Total Payable</p>
            <p class="text-lg font-bold text-purple-600">PKR {{ number_format($fee->total_amount, 2) }}</p>
        </div>
        <div class="p-4 bg-red-50 rounded-xl">
            <p class="text-sm text-red-600 mb-1">Late Fee</p>
            <p class="text-lg font-bold text-red-700">+ PKR {{ number_format($fee->late_fee, 2) }}</p>
        </div>
        <div class="p-4 bg-green-50 rounded-xl text-right">
            <p class="text-sm text-green-600 mb-1">Discount</p>
            <p class="text-lg font-bold text-green-700">- PKR {{ number_format($fee->discount, 2) }}</p>
        </div>
    </div>

    <div class="border-t border-gray-100 pt-4 mb-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm text-gray-500">Paid Amount</p>
            <p class="text-lg font-bold text-green-600">PKR {{ number_format($fee->payments->sum('amount_paid'), 2) }}</p>
        </div>
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Remaining Balance</p>
            <p class="text-lg font-bold text-gray-900">PKR {{ number_format($fee->total_amount - $fee->payments->sum('amount_paid'), 2) }}</p>
        </div>
    </div>

    <form action="{{ route('admin.fees.collect.store', $fee->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="late_fee" class="block text-sm font-medium text-gray-700 mb-1">Late Fee (Adjustment)</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-500 text-xs mt-0.5">PKR</span>
                    <input type="number" step="0.01" name="late_fee" id="late_fee"
                        value="{{ $fee->late_fee }}"
                        class="w-full pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                </div>
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-500 text-xs mt-0.5">PKR</span>
                    <input type="number" step="0.01" name="discount" id="discount"
                        value="{{ $fee->discount }}"
                        class="w-full pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                </div>
            </div>
            <div class="md:col-span-2">
                <label for="discount_reason" class="block text-sm font-medium text-gray-700 mb-1">Discount Reason</label>
                <input type="text" name="discount_reason" id="discount_reason" value="{{ $fee->discount_reason }}"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none" placeholder="e.g. Scholarship, Sibling Discount">
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-gray-100">
            <div>
                <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-500 text-xs mt-0.5">PKR</span>
                    <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                        value="{{ $fee->total_amount - $fee->payments->sum('amount_paid') }}"
                        max="{{ $fee->total_amount - $fee->payments->sum('amount_paid') }}"
                        required
                        class="w-full pl-8 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                </div>
            </div>

            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" id="payment_method" required
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="online">Online Payment</option>
                    <option value="check">Check</option>
                </select>
            </div>

            <div>
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks (Optional)</label>
                <textarea name="remarks" id="remarks" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.fees.collect.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-600/20 transition-all">
                Confirm Payment
            </button>
        </div>
    </form>
</div>
@endsection