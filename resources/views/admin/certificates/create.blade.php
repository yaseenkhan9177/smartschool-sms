@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Issue New Certificate
        </h2>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                <p class="text-indigo-800 text-sm font-medium">Select a student and a template to generate a certificate.</p>
            </div>

            <form action="{{ route('admin.certificates.preview') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select id="class_select" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                        <select name="student_id" id="student_select" required disabled class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                            <option value="">Select Class First</option>
                        </select>
                    </div>
                </div>

                <!-- Template Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Template</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($templates as $template)
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ $loop->first ? 'ring-2 ring-indigo-500 border-transparent' : 'border-gray-200' }}">
                            <input type="radio" name="template_id" value="{{ $template->id }}" {{ $loop->first ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">{{ $template->title }}</span>
                                <span class="block text-xs text-gray-500">{{ $template->type->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 transition-all flex items-center gap-2">
                        Next: Preview Certificate <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('class_select').addEventListener('change', function() {
        const classId = this.value;
        const studentSelect = document.getElementById('student_select');

        studentSelect.innerHTML = '<option value="">Loading...</option>';
        studentSelect.disabled = true;

        if (classId) {
            fetch(`{{ url('admin/certificates/get-students') }}/${classId}`)
                .then(response => response.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">Select Student</option>';
                    data.forEach(student => {
                        studentSelect.innerHTML += `<option value="${student.id}">${student.name} (${student.roll_no})</option>`;
                    });
                    studentSelect.disabled = false;
                });
        } else {
            studentSelect.innerHTML = '<option value="">Select Class First</option>';
        }
    });

    // Simple active state for radio buttons
    document.querySelectorAll('input[name="template_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="template_id"]').forEach(r => {
                r.closest('label').classList.remove('ring-2', 'ring-indigo-500', 'border-transparent');
                r.closest('label').classList.add('border-gray-200');
            });
            if (this.checked) {
                this.closest('label').classList.remove('border-gray-200');
                this.closest('label').classList.add('ring-2', 'ring-indigo-500', 'border-transparent');
            }
        });
    });
</script>
@endsection