@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Invoice #{{ $invoiceNo }}</h1>
            <p class="text-gray-500">Student: {{ $student->name }} ({{ $student->roll_number }})</p>
        </div>
        <a href="{{ route('admin.fees.collect.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
            Back to List
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fee Description</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount (PKR)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $total = 0; @endphp
                    @foreach($fees as $fee)
                    @php $total += $fee->amount + $fee->late_fee - $fee->discount; @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                            {{ $fee->feeStructure->feeCategory->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($fee->month === 'Remaining Balance')
                            Remaining Balance
                            @else
                            {{ \Carbon\Carbon::parse($fee->month)->format('M Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $fee->status == 'paid' ? 'bg-green-100 text-green-700' : 
                                   ($fee->status == 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($fee->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            @if($fee->status == 'paid' || $fee->status == 'partial')
                            <span class="font-bold">PKR {{ number_format($fee->amount, 2) }}</span>
                            <p class="text-xs text-gray-400">Locked (Paid/Partial)</p>
                            @else
                            <form id="update-form-{{ $fee->id }}" action="{{ route('admin.fees.item.update', $fee->id) }}" method="POST" class="flex items-center space-x-2" onsubmit="event.preventDefault(); confirmUpdate('{{ $fee->id }}')">
                                @csrf
                                @method('PUT')
                                <input type="number" name="amount" value="{{ $fee->amount }}" min="0" step="0.01" class="w-32 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit" class="text-blue-600 hover:text-blue-800" title="Update Amount">
                                    <i class="fa-solid fa-save"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($fee->status == 'paid' || $fee->status == 'partial')
                            <span class="text-gray-400 cursor-not-allowed" title="Cannot delete paid/partial item">
                                <i class="fa-solid fa-trash"></i>
                            </span>
                            @else
                            <form id="delete-form-{{ $fee->id }}" action="{{ route('admin.fees.item.remove', $fee->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('{{ $fee->id }}')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50" title="Remove Item">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-bold text-gray-800">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right">Total Invoice Amount:</td>
                        <td class="px-6 py-4">PKR {{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmUpdate(id) {
        Swal.fire({
            title: 'Update Fee Amount?',
            text: "You are about to modify the amount for this fee item.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('update-form-' + id).submit();
            }
        })
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Remove Fee Item?',
            text: "Are you sure you want to remove this fee from the invoice?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection