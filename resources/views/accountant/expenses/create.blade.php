@extends('layouts.accountant')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="expenseCategoryManager()">
    <div class="flex items-center gap-3">
        <a href="{{ route('accountant.expenses.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Expense</h1>
            <p class="text-gray-500 text-sm mt-1">Record a new school expense</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('accountant.expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Expense Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
                        placeholder="e.g. Office Supplies">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="expense_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <button type="button" @click="showModal = true" class="text-xs font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-plus"></i> Add New
                            </button>
                        </div>
                        <select name="expense_category_id" id="expense_category_id" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            <option value="">Select Category</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name" :selected="selectedCategoryId == category.id"></option>
                            </template>
                        </select>
                        @error('expense_category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-500">PKR</span>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                                class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
                                placeholder="0.00">
                        </div>
                        @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                    @error('expense_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
                        placeholder="Additional details...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">Receipt Image (Optional)</label>
                    <input type="file" name="receipt" id="receipt" accept="image/*,application/pdf"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, PDF. Max size: 2MB</p>
                    @error('receipt')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('accountant.expenses.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-600/20 transition-all">
                    Save Expense
                </button>
            </div>
        </form>
    </div>
    <!-- Category Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
        <div @click.away="showModal = false" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative">
            <button type="button" @click="showModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Category</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="newCategoryName" @keydown.enter="saveCategory()"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
                        placeholder="e.g. Utility Bills">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <textarea x-model="newCategoryDesc" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
                        placeholder="Additional details..."></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                <button type="button" @click="saveCategory()" :disabled="isSaving"
                    class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                    <span x-show="!isSaving">Save Category</span>
                    <span x-show="isSaving"><i class="fa-solid fa-spinner fa-spin"></i> Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('expenseCategoryManager', () => ({
            showModal: false,
            newCategoryName: '',
            newCategoryDesc: '',
            isSaving: false,
            categories: @json($categories),
            selectedCategoryId: "{{ old('expense_category_id') }}",

            async saveCategory() {
                if (!this.newCategoryName.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Category name is required!'
                    });
                    return;
                }

                this.isSaving = true;

                try {
                    const response = await fetch("{{ route('accountant.expenses.categories.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.newCategoryName,
                            description: this.newCategoryDesc
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Error saving category');
                    }

                    if (data.success) {
                        // Add new category to the list
                        this.categories.push(data.category);
                        // Select the newly created category
                        this.selectedCategoryId = data.category.id;
                        document.getElementById('expense_category_id').value = data.category.id;

                        // Close modal and reset
                        this.showModal = false;
                        this.newCategoryName = '';
                        this.newCategoryDesc = '';

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Category added successfully',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message
                    });
                } finally {
                    this.isSaving = false;
                }
            }
        }));
    });
</script>
@endsection