@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Link -->
    <a href="{{ route('accountant.students.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-6">
        <i class="fa-solid fa-arrow-left"></i> Back to Students
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl mx-auto">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900">Add New Student</h2>
            <p class="text-slate-500 text-sm mt-1">Fill in the details to register a new student.</p>
        </div>

        <form action="{{ route('accountant.students.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            <input type="hidden" name="family_id" id="family_id" value="">

            <!-- Section 1: Student Information -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="John Doe" value="{{ old('name') }}">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="student@example.com" value="{{ old('email') }}">
                    </div>

                    <!-- Class -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Admission Class <span class="text-red-500">*</span></label>
                        <select name="class_id" id="class_id" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phone (Optional) -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="+1 234 567 890" value="{{ old('phone') }}">
                    </div>

                    <!-- Profile Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200 overflow-hidden" id="preview-container">
                                <i class="fa-solid fa-user text-3xl text-gray-400" id="placeholder-icon"></i>
                                <img src="" alt="Preview" class="w-full h-full object-cover hidden" id="image-preview">
                            </div>
                            <div>
                                <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                                <label for="image" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">
                                    Upload Photo
                                </label>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Parent / Guardian Information -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Parent / Guardian Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Parent Name -->
                    <div>
                        <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Parent Name</label>
                        <input type="text" name="parent_name" id="parent_name"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Guardian Name" value="{{ old('parent_name') }}">
                    </div>

                    <!-- Parent Phone -->
                    <div>
                        <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Parent Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="parent_phone" id="parent_phone" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="01234567890" value="{{ old('parent_phone') }}"
                            minlength="11" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits">
                    </div>
                </div>
            </div>

            <!-- Section 3: Account Security -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Security</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="••••••••">
                            <button type="button" onclick="generatePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-blue-600 font-medium hover:text-blue-800">Generate</button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('accountant.students.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5">
                    Create Student
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('placeholder-icon').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generatePassword() {
        const length = 10;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        document.getElementById('password').value = retVal;
        document.getElementById('password_confirmation').value = retVal;

        // Show visibility toggle or alert user? For now just fill it.
        // Maybe make type text momentarily?
        const pwdInput = document.getElementById('password');
        pwdInput.type = "text";
        setTimeout(() => pwdInput.type = "password", 3000);
    }

    // Auto-fill Parent Details
    const parentEmailInput = document.getElementById('parent_email');

    // Family auto-link feature via email
    if (parentEmailInput) {
        parentEmailInput.addEventListener('blur', function() {
            const email = this.value;
            if (email && email.includes('@')) {
                // Accountant context check URL
                const checkUrl = '{{ route("accountant.families.check") }}';

                fetch(checkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            father_email: email
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.exists) {
                            let childrenHtml = data.children.map(c => `<li class="ml-2 py-0.5"><i class="fa-solid fa-child text-indigo-400 mr-2"></i> ${c.name} <span class="text-xs text-gray-400">(${c.class})</span></li>`).join('');
                            Swal.fire({
                                title: '👨‍👩‍👧 Family Found!',
                                html: `
                                <div class="text-left bg-gray-50/80 p-5 rounded-xl text-sm border border-gray-200 shadow-inner mt-2">
                                    <p class="font-bold text-gray-900 mb-3 flex items-center justify-between">
                                        <span><i class="fa-solid fa-user-tie text-indigo-500 mr-2"></i> ${data.father_name}</span>
                                        <span class="text-[10px] uppercase tracking-wider font-bold bg-indigo-100 text-indigo-700 px-2 py-1 rounded-lg border border-indigo-200">${data.family_code}</span>
                                    </p>
                                    <p class="text-gray-500 font-semibold text-[10px] uppercase tracking-wider mb-2 border-b border-gray-200 pb-1">Linked Children</p>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        ${childrenHtml}
                                    </ul>
                                </div>
                                <p class="text-xs text-gray-500 mt-4"><i class="fa-solid fa-circle-info mr-1"></i> Linking this student keeps all siblings under one parent portal.</p>
                            `,
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: '<i class="fa-solid fa-link mr-1"></i> Link to Family',
                                cancelButtonText: '<i class="fa-solid fa-plus mr-1"></i> Create New',
                                confirmButtonColor: '#4f46e5',
                                cancelButtonColor: '#9ca3af',
                                reverseButtons: true,
                                customClass: {
                                    confirmButton: 'rounded-xl shadow-lg shadow-indigo-500/30 px-5 py-2.5 font-semibold transition-all',
                                    cancelButton: 'rounded-xl border border-gray-300 px-5 py-2.5 font-semibold hover:bg-gray-50 transition-all text-gray-700'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('family_id').value = data.family_id;
                                    Swal.fire({
                                        title: 'Linked! 🔗',
                                        text: 'Student will be added to this existing family.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        customClass: {
                                            popup: 'rounded-2xl'
                                        }
                                    });
                                } else {
                                    document.getElementById('family_id').value = '';
                                }
                            });
                        }
                    })
                    .catch(err => console.error('Family check error:', err));
            }
        });
    }
</script>
@endsection