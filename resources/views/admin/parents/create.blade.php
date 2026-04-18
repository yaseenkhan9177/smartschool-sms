@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.parents.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Add New Parent</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.parents.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Name</label>
                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number (Login ID)</label>
                <input type="text" name="phone" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">This will be used for login.</p>
            </div>

            <!-- Email (Optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                <input type="email" name="email" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Link Student -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Link Children (Optional)</h3>
                    <button type="button" onclick="addStudentField()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="fa-solid fa-plus mr-1"></i> Add Another Child
                    </button>
                </div>

                <div id="student-fields" class="space-y-3">
                    <div class="student-input-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student Roll Number</label>
                        <div class="flex gap-2">
                            <input type="text" name="student_roll_numbers[]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter Roll Number">
                            <button type="button" onclick="removeStudentField(this)" class="px-3 text-red-500 hover:text-red-700 hidden remove-btn">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Enter roll numbers to automatically link students to this parent.</p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Parent Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addStudentField() {
        const container = document.getElementById('student-fields');
        const count = container.children.length;

        const div = document.createElement('div');
        div.className = 'student-input-group';
        div.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Student Roll Number</label>
            <div class="flex gap-2">
                <input type="text" name="student_roll_numbers[]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter Roll Number">
                <button type="button" onclick="removeStudentField(this)" class="px-3 text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);

        // Show all remove buttons if more than 1
        checkRemoveButtons();
    }

    function removeStudentField(btn) {
        btn.closest('.student-input-group').remove();
        checkRemoveButtons();
    }

    function checkRemoveButtons() {
        const groups = document.querySelectorAll('.student-input-group');
        groups.forEach((group, index) => {
            const removeBtn = group.querySelector('.remove-btn');
            if (removeBtn) {
                if (groups.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            }
        });
    }

    // Initial check when the page loads
    document.addEventListener('DOMContentLoaded', checkRemoveButtons);
</script>
@endsection