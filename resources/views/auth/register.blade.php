<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AstriaLearning SMS</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">

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
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },
                            '33%': {
                                transform: 'translate(30px, -50px) scale(1.1)'
                            },
                            '66%': {
                                transform: 'translate(-20px, 20px) scale(0.9)'
                            },
                            '100%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Custom file input styling */
        input[type="file"]::file-selector-button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            margin-right: 1rem;
            transition: background-color 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-300 font-sans antialiased selection:bg-blue-500 selection:text-white min-h-screen flex items-center justify-center relative overflow-x-hidden py-12">

    <!-- Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Back Link -->
    <a href="/" class="absolute top-6 left-6 text-slate-400 hover:text-white transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
    </a>

    <!-- Register Card -->
    <div class="w-full max-w-2xl px-4">
        <div class="glass-card rounded-2xl p-8 md:p-10 shadow-2xl">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('assets/img/logo-round.jpg') }}" alt="Own Education" class="h-24 w-24 object-contain rounded-full">
                </div>
                <!-- <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 mx-auto mb-4">
                    <i class="fa-solid fa-user-plus text-xl text-white"></i>
                </div> -->
                <h2 class="text-2xl font-bold text-white">Create Account</h2>
                <p class="text-slate-400 text-sm mt-2">Join us to start your academic journey</p>
            </div>

            <form action="{{ route('student.register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-user text-slate-500"></i>
                            </div>
                            <input type="text" name="name" id="name" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="John Doe">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-slate-500"></i>
                            </div>
                            <input type="email" name="email" id="email" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-300 mb-2">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-slate-500 text-xs"></i>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10 transition-all bg-gray-50/50 hover:bg-white focus:bg-white"
                                placeholder="01234567890" pattern="\d{11}" minlength="11" maxlength="11" title="Please enter exactly 11 digits">
                        </div>
                    </div>

                    <!-- Parent Phone -->
                    <div>
                        <label for="parent_phone" class="block text-sm font-medium text-slate-300 mb-2">Parent/Guardian Phone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-slate-500 text-xs"></i>
                            </div>
                            <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10 transition-all bg-gray-50/50 hover:bg-white focus:bg-white"
                                placeholder="01234567890" pattern="\d{11}" minlength="11" maxlength="11" title="Please enter exactly 11 digits">
                        </div>
                    </div>



                    <!-- Profile Image -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Profile Picture</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="profile_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-700 border-dashed rounded-xl cursor-pointer bg-slate-800/30 hover:bg-slate-800/50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-500 mb-2"></i>
                                    <p id="file-name" class="text-sm text-slate-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-500">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                                </div>
                                <input id="profile_image" name="profile_image" type="file" class="hidden" accept="image/*" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Click to upload or drag and drop'" />
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-slate-500"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-slate-500"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-500 shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-blue-600">
                        Create Account
                    </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-400 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>