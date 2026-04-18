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
        <form id="fee-collection-form" action="{{ request()->routeIs('admin.*') ? route('admin.fees.store-single') : route('accountant.fees.store-single') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="transaction_id" id="transaction_id">

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
                <button type="submit" id="submit-btn" class="px-5 py-2.5 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Save Fee Record & Generate Card
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Offline Success Receipt Modal (Hybrid) -->
<div id="hybrid-receipt-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-yellow-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa-solid fa-wifi-slash text-yellow-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Payment Recorded (Offline)</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">The fee record has been saved locally on your device. It will automatically sync to the server once you are back online.</p>
                        </div>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-xs font-bold text-gray-400 uppercase">Temp Receipt ID</span>
                                <span class="text-xs font-mono font-bold text-gray-700" id="temp-receipt-id">#TEMP-XXXX</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs font-bold text-gray-400 uppercase">Student</span>
                                <span class="text-xs font-bold text-gray-700" id="receipt-student">Student Name</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs font-bold text-gray-400 uppercase">Amount</span>
                                <span class="text-xs font-bold text-indigo-600" id="receipt-amount">Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-bold text-gray-400 uppercase">Status</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700 uppercase"><i class="fa-solid fa-clock mr-1"></i> Pending Sync</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="location.reload()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Create Another</button>
                <button type="button" onclick="window.print()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Print Temp Receipt</button>
                <a href="{{ request()->routeIs('admin.*') ? route('admin.fees.collect.index') : route('accountant.fees.collect.index') }}" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Back to List</a>
            </div>
        </div>
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

        // --- Offline / PWA Logic ---
        const form = document.getElementById('fee-collection-form');
        const transactionIdInput = document.getElementById('transaction_id');
        const submitBtn = document.getElementById('submit-btn');

        // 1. Generate Transaction ID (UUID)
        function generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
        transactionIdInput.value = generateUUID();

        // 2. Intercept Form Submit
        form.addEventListener('submit', async function(e) {
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing...';

            if (!navigator.onLine) {
                e.preventDefault();
                console.log("Offline detected. Saving to IndexedDB...");

                // Construct Fee Object
                const formData = new FormData(form);
                const feeData = {};
                formData.forEach((value, key) => {
                    feeData[key] = value;
                });

                // Add metadata for sync tracking
                feeData.status = 'pending_sync';
                feeData.retry_count = 0;
                feeData.created_at = new Date().toISOString();
                
                // Add Server Validation Hash
                const hashSource = feeData.student_id + '|' + feeData.base_amount + '|' + feeData.month;
                feeData.validation_hash = await window.generateValidationHash(hashSource);
                
                // Add to form for online submission as well if needed
                let hashInput = document.getElementById('validation_hash');
                if(!hashInput) {
                    hashInput = document.createElement('input');
                    hashInput.type = 'hidden';
                    hashInput.name = 'validation_hash';
                    hashInput.id = 'validation_hash';
                    form.appendChild(hashInput);
                }
                hashInput.value = feeData.validation_hash;

                // Also capture display values for the hybrid receipt
                const studentName = document.getElementById('student_id').options[document.getElementById('student_id').selectedIndex].text;
                const totalAmount = document.getElementById('grand_total_display').textContent;

                try {
                    // Save to IndexedDB
                    if (window.smsDbPromise) {
                        const db = await window.smsDbPromise;
                        await db.put('fees', feeData);

                        // Trigger registration of sync if available
                        if ('serviceWorker' in navigator && 'SyncManager' in window) {
                            const registration = await navigator.serviceWorker.ready;
                            await registration.sync.register('sync-fees');
                        }

                        // Show Hybrid Receipt Modal
                        document.getElementById('temp-receipt-id').textContent = '#TEMP-' + feeData.transaction_id.substring(0, 8).toUpperCase();
                        document.getElementById('receipt-student').textContent = studentName;
                        document.getElementById('receipt-amount').textContent = totalAmount;
                        document.getElementById('hybrid-receipt-modal').classList.remove('hidden');
                        
                        // Scroll to top
                        window.scrollTo(0, 0);

                    } else {
                        throw new Error("IndexedDB not initialized");
                    }
                } catch (err) {
                    console.error("Failed to save offline:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Offline Save Failed',
                        text: 'Unable to save record locally. Please check storage permissions.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-save mr-2"></i> Save Fee Record & Generate Card';
                }
            }
            // If online, let it submit normally
        });
    });
</script>
@endsection
@endsection