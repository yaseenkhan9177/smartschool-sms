<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration | Own Education</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
</head>

<body class="bg-slate-900 text-slate-200 font-sans antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-4xl bg-slate-800 rounded-2xl shadow-2xl overflow-hidden border border-slate-700/50 flex flex-col md:flex-row">

        <!-- Left Banner -->
        <div class="hidden md:flex md:w-1/3 bg-blue-600 p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-600 to-blue-800 opacity-90"></div>
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-purple-500 rounded-full blur-3xl opacity-50"></div>

            <div class="relative z-10">
                <div class="w-24 h-24 mb-6 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                    <img src="{{ asset('assets/img/logo-round.jpg') }}" class="w-full h-full object-cover rounded-full">
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Welcome!</h2>
                <p class="text-blue-100">Register your child to get started with our digital campus.</p>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                    <div class="w-10 h-10 rounded-full bg-green-400 flex items-center justify-center text-green-900">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">Admissions Open</p>
                        <p class="text-xs text-blue-100">Academic Session 2026</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form -->
        <div class="w-full md:w-2/3 p-8 lg:p-12 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-white">Parent Registration</h2>
                    <p class="text-slate-400 text-sm">Please fill in the details below to register.</p>
                </div>
                <!-- Already have account link -->
                <a href="{{ route('student.login') }}" class="text-xs font-semibold text-blue-400 hover:text-blue-300 transition-colors">
                    Already registered? Login
                </a>
            </div>

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('student.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Parent Details -->
                <div class="pb-4 border-b border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-300 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-blue-500"></i> Parent / Guardian Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Parent Name -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Parent Full Name</label>
                            <div class="relative">
                                <i class="fa-solid fa-user absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="text" name="parent_name" value="{{ old('parent_name') }}" placeholder="e.g. John Doe" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                        </div>

                        <!-- Parent Phone -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Mobile Number (Crucial)</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" required
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10 transition-all bg-gray-50/50 hover:bg-white focus:bg-white"
                                    placeholder="01234567890" pattern="\d{11}" minlength="11" maxlength="11" title="Please enter exactly 11 digits">
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1">Used for login & SMS alerts (approx 11 digits).</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Student Details -->
                <div class="pb-4 border-b border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-300 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-child text-pink-500"></i> Student Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Student Name -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Student Full Name</label>
                            <div class="relative">
                                <i class="fa-solid fa-user-graduate absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Student Name" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                        </div>

                        <!-- Roll Number -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Student Roll Number</label>
                            <div class="relative">
                                <i class="fa-solid fa-id-card absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="text" name="roll_number" value="{{ old('roll_number') }}" placeholder="Roll No."
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                        </div>

                        <!-- Class -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Admission Class</label>
                            <div class="relative">
                                <i class="fa-solid fa-layer-group absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <select name="class_id" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 transition-all outline-none appearance-none">
                                    <option value="" disabled selected>Select Class</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-slate-500 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Student Phone (Optional) -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Student Phone (Optional)</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10 transition-all bg-gray-50/50 hover:bg-white focus:bg-white"
                                    placeholder="01234567890" pattern="\d{11}" minlength="11" maxlength="11" title="Please enter exactly 11 digits">
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Student Photo</label>
                            <div class="relative group">
                                <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*" onchange="previewImage(event)">
                                <label for="profile_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-600 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-slate-700/30 transition-all group-hover:text-blue-400">
                                    <div id="image_preview_container" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-500 mb-2 group-hover:text-blue-500 transition-colors"></i>
                                        <p class="text-sm text-slate-400">Click to upload student photo</p>
                                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG (Max 2MB)</p>
                                    </div>
                                    <img id="image_preview" class="hidden h-full w-full object-contain rounded-xl" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Account Security -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-300 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Account Security
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Email Address</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="student@example.com" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1">Used for login and resetting passwords.</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="password" name="password" placeholder="••••••••" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase">Confirm Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-check-double absolute left-3 top-3.5 text-slate-500 text-sm"></i>
                                <input type="password" name="password_confirmation" placeholder="••••••••" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-200 placeholder-slate-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold hover:from-blue-500 hover:to-blue-400 shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-blue-600 mt-4">
                    Complete Registration
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image_preview');
                const container = document.getElementById('image_preview_container');
                output.src = reader.result;
                output.classList.remove('hidden');
                container.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>