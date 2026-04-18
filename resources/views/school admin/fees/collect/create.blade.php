@php
$layout = request()->routeIs('admin.*') ? 'layouts.admin' : 'layouts.accountant';
@endphp

@extends($layout)

@section('title', 'Create Student Fee')

@section('content')
<div class="sm:p-6 p-4">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create Individual Fee</h1>
            <p class="text-sm text-gray-500 mt-1">Generate a fee card for a specific student</p>
        </div>
        <a href="{{ request()->routeIs('admin.*') ? route('admin.fees.collect.index') : route('accountant.fees.collect.index') }}" class="text-sm bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium shadow-sm transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Collection
        </a>
    </div>

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg" role="alert">
        <div class="font-bold flex items-center mb-1"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Error</div>
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ request()->routeIs('admin.*') ? route('admin.fees.store-single') : route('accountant.fees.store-single') }}" method="POST" class="p-6">
            @csrf

            <!-- Section 1: Core Details -->
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><i class="fa-solid fa-user-graduate text-indigo-500 mr-2"></i> Student Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Student <span class="text-red-500">*</span></label>
                    <select name="student_id" id="student_id" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choose Student --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" data-class="{{ $student->class_id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} (Roll: {{ $student->roll_number }}) - {{ $student->schoolClass->name ?? 'N/A' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fee Category/Structure <span class="text-red-500">*</span></label>
                    <select name="fee_structure_id" id="fee_structure_id" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choose Fee Structure --</option>
                        @foreach($feeStructures as $fs)
                        <option value="{{ $fs->id }}" data-amount="{{ $fs->amount }}" {{ old('fee_structure_id') == $fs->id ? 'selected' : '' }}>
                            {{ $fs->feeCategory->name }} (Rs. {{ $fs->amount }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Month <span class="text-red-500">*</span></label>
                    <input type="month" name="month" value="{{ old('month', date('Y-m')) }}" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-10')) }}" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Section 2: Financial Breakdown -->
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><i class="fa-solid fa-file-invoice-dollar text-green-500 mr-2"></i> Amount Breakdown</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Base Amount (Tuition, etc.) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="base_amount" id="base_amount" value="{{ old('base_amount', 0) }}" required min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Admission Fee (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="admission_fee" id="admission_fee" value="{{ old('admission_fee', 0) }}" min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Exam Fee (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="exam_fee" id="exam_fee" value="{{ old('exam_fee', 0) }}" min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Transport Fee (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="transport_fee" id="transport_fee" value="{{ old('transport_fee', 0) }}" min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Late Fine (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="late_fee" id="late_fee" value="{{ old('late_fee', 0) }}" min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Discount (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}" min="0" step="0.01" class="amount-input pl-10 w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-red-600">
                    </div>
                </div>
            </div>

            <!-- Grand Total Display -->
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase">Calculated Grand Total</h3>
                    <p class="text-gray-600 text-xs mt-1">Base + Admsn + Exam + Trnsprt + Fine - Discount</p>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-black text-indigo-700" id="grand_total_display">Rs. 0.00</span>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Private Note (Optional)</label>
                <textarea name="note" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Concession applied manually.">{{ old('note') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ request()->routeIs('admin.*') ? route('admin.fees.collect.index') : route('accountant.fees.collect.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Save Fee Record & Generate Card
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const structureSelect = document.getElementById('fee_structure_id');
        const baseAmountInput = document.getElementById('base_amount');
        const allAmountInputs = document.querySelectorAll('.amount-input');
        const totalDisplay = document.getElementById('grand_total_display');

        // Auto-fill base amount when structure is chosen
        structureSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const amount = selectedOption.getAttribute('data-amount');
                baseAmountInput.value = amount || 0;
            }
            calculateTotal();
        });

        // Calculate grand total dynamically
        function calculateTotal() {
            let base = parseFloat(document.getElementById('base_amount').value) || 0;
            let admission = parseFloat(document.getElementById('admission_fee').value) || 0;
            let exam = parseFloat(document.getElementById('exam_fee').value) || 0;
            let transport = parseFloat(document.getElementById('transport_fee').value) || 0;
            let late = parseFloat(document.getElementById('late_fee').value) || 0;
            let discount = parseFloat(document.getElementById('discount').value) || 0;

            let total = (base + admission + exam + transport + late) - discount;

            if (total < 0) total = 0;

            totalDisplay.textContent = 'Rs. ' + total.toFixed(2);
        }

        allAmountInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        // Initial calculation
        calculateTotal();
    });
</script>
@endsection
@endsection