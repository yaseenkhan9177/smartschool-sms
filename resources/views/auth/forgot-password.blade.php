<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SmartSchool SMS</title>
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
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    animation: { 'blob': 'blob 7s infinite' },
                    keyframes: {
                        blob: {
                            '0%':   { transform: 'translate(0px, 0px) scale(1)' },
                            '33%':  { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%':  { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
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
        .role-badge {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-300 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Effects -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-amber-500/10 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob" style="animation-delay: 4s;"></div>
    </div>

    <!-- Back Link -->
    <a href="{{ route('login') }}" class="absolute top-6 left-6 text-slate-400 hover:text-white transition-colors flex items-center gap-2 text-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Login
    </a>

    <!-- Card -->
    <div class="w-full max-w-lg p-6 mx-4">
        <div class="glass-card rounded-2xl p-8 shadow-2xl">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('assets/img/logo-round.jpg') }}" alt="SmartSchool SMS" class="h-20 w-auto object-contain rounded-full">
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20 mx-auto mb-4">
                    <i class="fa-solid fa-key text-xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Forgot Password?</h1>
                <p class="text-slate-400 text-sm mt-2">No worries! Here's how to recover your account.</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mb-6 text-sm text-blue-300">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i>
                    <p>Password reset emails are not enabled in this system. Please contact your administrator directly to have your password reset.</p>
                </div>
            </div>

            <!-- Role-based Instructions -->
            <div class="space-y-3 mb-8">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">Who to contact based on your role:</h2>

                <div class="flex items-center gap-3 p-3 role-badge rounded-xl">
                    <div class="w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-graduation-cap text-indigo-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">Student</p>
                        <p class="text-slate-400 text-xs">Contact your <span class="text-indigo-400">School Admin or Accountant</span> to reset your password.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 role-badge rounded-xl">
                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-chalkboard-user text-purple-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">Teacher</p>
                        <p class="text-slate-400 text-xs">Contact your <span class="text-purple-400">School Admin</span> to reset your password.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 role-badge rounded-xl">
                    <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-users text-green-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">Parent</p>
                        <p class="text-slate-400 text-xs">Contact your <span class="text-green-400">School Admin or Accountant</span> to reset your password.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 role-badge rounded-xl">
                    <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-calculator text-cyan-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">Accountant</p>
                        <p class="text-slate-400 text-xs">Contact your <span class="text-cyan-400">School Admin</span> to reset your password.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 role-badge rounded-xl">
                    <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-shield-halved text-amber-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">School Admin</p>
                        <p class="text-slate-400 text-xs">Contact the <span class="text-amber-400">Super Admin</span> — they can reset your password from the admin panel.</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('login') }}" class="block w-full py-3 text-center rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-500 shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-arrow-left mr-2"></i> Return to Login
            </a>
        </div>
    </div>

</body>

</html>
