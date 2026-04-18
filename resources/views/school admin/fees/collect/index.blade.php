@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ 
    showCollectModal: false, 
    selectedFee: null, 
    selectedInvoiceNo: null,
    feeBaseAmount: 0, 
    feeLate: 0, 
    feeDiscount: 0, 
    feePaid: 0, 
    payable: 0,
    waiver: 0,
    amount_paid: 0,
    invoiceItems: [],
    calculatePayable() {
        let basePayable = (this.feeBaseAmount + this.feeLate - this.feeDiscount) - this.feePaid;
        return Math.max(0, basePayable - (parseFloat(this.waiver) || 0));
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Fee Collection</h1>
            <p class="text-gray-500 text-sm mt-1">Manage student fees and payments</p>
        </div>
        <button onclick="document.getElementById('generateModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20 flex items-center gap-2">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            Generate Bulk Challan
        </button>
        <button onclick="document.getElementById('generateTransportModal').classList.remove('hidden')" class="px-4 py-2 bg-amber-600 text-white rounded-xl text-sm font-medium hover:bg-amber-700 transition-colors shadow-lg shadow-amber-600/20 flex items-center gap-2">
            <i class="fa-solid fa-bus"></i>
            Generate Transport Fees
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-medium border border-green-100 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium border border-red-100 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.fees.collect.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="class_id" class="block text-xs font-medium text-gray-700 mb-1">Class</label>
                <div class="relative">
                    <i class="fa-solid fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select name="class_id" id="class_id" class="w-40 pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none appearance-none">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="month" class="block text-xs font-medium text-gray-700 mb-1">Month</label>
                <div class="relative">
                    <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="month" name="month" id="month" value="{{ request('month') }}" class="w-40 pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none">
                </div>
            </div>
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select name="status" id="status" class="w-40 pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none appearance-none">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search Student</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, ID or Email" class="w-48 pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-xl text-sm font-medium hover:bg-gray-900 transition-colors shadow-lg shadow-gray-800/20">
                <i class="fa-solid fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Bulk Print Form -->
    <form action="{{ route('admin.fees.bulk-print') }}" method="POST" target="_blank" id="bulkPrintForm">
        @csrf
        <!-- Bulk Actions Toolbar -->
        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 items-center justify-between mb-4 hidden" id="bulkActions">
            <div class="flex items-center gap-2 text-purple-700 font-medium">
                <i class="fa-solid fa-check-double"></i>
                <span id="selectedCount">0</span> selected
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Selected Challans
            </button>
        </div>

        <!-- Fees List -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium w-4">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            </th>
                            <th class="px-6 py-4 font-medium">Student (Invoice)</th>
                            <th class="px-6 py-4 font-medium">Invoice Breakdown</th>
                            <th class="px-6 py-4 font-medium">Month</th>
                            <th class="px-6 py-4 font-medium">Total Amount</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_fees[]" value="{{ optional($invoice->sub_fees->first())->id ?? '' }}" class="fee-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center text-xs font-bold text-purple-600">
                                        {{ substr($invoice->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $invoice->student->name }}
                                            @if($invoice->status == 'paid' && $invoice->sub_fees->isNotEmpty())
                                            <a href="{{ route('admin.fees.invoice', $invoice->sub_fees->first()->id) }}" target="_blank" class="text-xs text-purple-600 hover:text-purple-800 ml-1" title="View Invoice">
                                                <i class="fa-solid fa-file-invoice"></i> {{ $invoice->invoice_no }}
                                            </a>
                                            @else
                                            <span class="text-xs text-gray-400 ml-1">{{ $invoice->invoice_no }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $invoice->student->schoolClass->name ?? 'N/A' }} | ID: {{ $invoice->student->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex flex-col gap-1">
                                    @foreach($invoice->sub_fees as $subFee)
                                    <span class="inline-flex items-center text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700">
                                        {{ $subFee->feeStructure->feeCategory->name ?? 'Custom/Transport Fee' }}: {{ number_format($subFee->amount, 0) }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($invoice->month === 'Remaining Balance')
                                Remaining Balance
                                @else
                                {{ \Carbon\Carbon::parse($invoice->month)->format('F Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                PKR {{ number_format($invoice->final_total, 2) }}
                                @if($invoice->total_late_fee > 0)
                                <span class="text-xs text-red-500 block">+ {{ number_format($invoice->total_late_fee, 0) }} Late</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($invoice->status == 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fa-solid fa-check mr-1"></i>Paid
                                </span>
                                @elseif($invoice->status == 'partial')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <i class="fa-solid fa-clock mr-1"></i>Partial
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <i class="fa-solid fa-xmark mr-1"></i>Unpaid
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($invoice->status != 'paid')
                                    <button type="button" @click="
                                        selectedInvoiceNo = '{{ $invoice->invoice_no ?: 'id-' . $invoice->sub_fees->first()->id }}'; 
                                        feeBaseAmount = {{ $invoice->final_total }}; 
                                        feeLate = {{ $invoice->total_late_fee }};
                                        feeDiscount = {{ $invoice->total_discount }};
                                        feePaid = {{ $invoice->total_paid }};
                                        invoiceItems = {{ json_encode($invoice->sub_fees->map(function($f) {
                                            return [
                                                'name' => $f->feeStructure->feeCategory->name ?? 'Custom/Transport Fee',
                                                'amount' => $f->amount,
                                                'late' => $f->late_fee,
                                                'discount' => $f->discount,
                                                'paid' => $f->payments->sum('amount_paid')
                                            ];
                                        })) }};
                                        waiver = 0;
                                        amount_paid = 0;
                                        showCollectModal = true;
                                    " class="px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                        Collect
                                    </button>
                                    @endif

                                    @if($invoice->sub_fees->isNotEmpty())
                                    <a href="{{ route('admin.fees.edit', $invoice->invoice_no ?: 'id-' . $invoice->sub_fees->first()->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors shadow-sm flex items-center gap-1.5">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>

                                    @if($invoice->status == 'paid' || $invoice->status == 'partial')
                                    <a href="{{ route('admin.fees.invoice', $invoice->sub_fees->first()->id) }}" target="_blank" class="px-3 py-1.5 bg-green-50 text-green-600 border border-green-200 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors shadow-sm flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-invoice"></i> Receipt
                                    </a>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-folder-open text-gray-400"></i>
                                    </div>
                                    <p class="text-sm font-medium">No fee records found</p>
                                    <p class="text-xs text-gray-400 mt-1">Try adjusting your filters or generate new fees</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $invoices->links() }}
            </div>
        </div>
    </form>

    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.fee-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkActions() {
            const checked = document.querySelectorAll('.fee-checkbox:checked');
            selectedCount.textContent = checked.length;
            if (checked.length > 0) {
                bulkActions.classList.remove('hidden');
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    </script>

    <!-- Collect Modal -->
    <div x-show="showCollectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCollectModal" @click="showCollectModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'{{ route('admin.fees.collect.store', '') }}/' + selectedInvoiceNo" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Collect Fee Invoice</h3>
                            <button type="button" @click="showCollectModal = false" class="text-gray-400 hover:text-gray-500">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Invoice Breakdown Panel -->
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-500 uppercase font-bold mb-2">Invoice Details</p>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <template x-for="item in invoiceItems" :key="item.name">
                                        <li class="flex justify-between border-b border-gray-100 last:border-0 pb-1 last:pb-0">
                                            <span x-text="item.name"></span>
                                            <div>
                                                <span class="font-medium" x-text="'PKR ' + item.amount"></span>
                                                <template x-if="item.paid > 0">
                                                    <span class="text-xs text-green-600 ml-1" x-text="'(Paid: ' + item.paid + ')'"></span>
                                                </template>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <!-- Summary -->
                            <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-purple-600 uppercase font-bold">Total Fee</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="'PKR ' + parseFloat(feeBaseAmount + feeLate).toFixed(2)"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-purple-600 uppercase font-bold">Prev. Paid + Discount</p>
                                    <p class="text-sm font-bold text-green-600" x-text="'PKR ' + parseFloat(feePaid + feeDiscount).toFixed(2)"></p>
                                </div>
                                <div class="col-span-2 border-t border-purple-200 pt-2 flex justify-between items-center">
                                    <p class="text-sm font-bold text-gray-700">Net Payable</p>
                                    <p class="text-xl font-bold text-purple-700" x-text="'PKR ' + calculatePayable().toFixed(2)"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-1">Amount Received</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">PKR</span>
                                        </div>
                                        <input type="number" step="0.01" name="amount_paid" id="amount_paid" required
                                            x-model="amount_paid"
                                            :max="calculatePayable()"
                                            @input="if(parseFloat($el.value) > calculatePayable()) $el.value = calculatePayable().toFixed(2); amount_paid = $el.value"
                                            class="w-full pl-12 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none font-bold text-lg text-gray-800"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label for="waiver" class="block text-sm font-medium text-gray-700 mb-1">Waiver / Discount</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">PKR</span>
                                        </div>
                                        <input type="number" step="0.01" name="waiver" id="waiver"
                                            x-model="waiver"
                                            class="w-full pl-12 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none font-medium text-gray-800"
                                            placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="check">Check</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks / Notes</label>
                                <textarea name="remarks" id="remarks" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none text-sm" placeholder="e.g. Paid by Uncle, Late fee waived..."></textarea>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="print_receipt" id="print_receipt" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="print_receipt" class="text-sm text-gray-700">Print Receipt after saving</label>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 text-right">
                                    Remaining Balance:
                                    <span class="font-bold" x-text="'PKR ' + Math.max(0, calculatePayable() - amount_paid).toFixed(2)"
                                        :class="calculatePayable() - amount_paid > 0 ? 'text-red-500' : 'text-green-500'">
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Payment
                        </button>
                        <button type="button" @click="showCollectModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div id="generateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('generateModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.fees.generate') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Generate Tuition/Class Fees</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="gen_class_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select name="class_id" id="gen_class_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fee Categories (Select multiple)</label>
                            <div class="space-y-2 max-h-32 overflow-y-auto p-2 border border-gray-200 rounded-xl bg-gray-50">
                                @foreach($feeCategories as $category)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" checked class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="gen_month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                            <input type="month" name="month" id="gen_month" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" id="due_date" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                        </div>
                    </div>

                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Generate
                    </button>
                    <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Transport Modal -->
<div id="generateTransportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title-transport" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('generateTransportModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.transport.fees.generate') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-bus text-xl"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-transport">Generate Transport Fees</h3>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">This will generate transport fees for all students with active transport routes for the selected month.</p>

                    <div class="space-y-4">
                        <div>
                            <label for="transport_month" class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                            <input type="month" name="month" id="transport_month" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                        </div>
                    </div>

                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Generate Fees
                    </button>
                    <button type="button" onclick="document.getElementById('generateTransportModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection