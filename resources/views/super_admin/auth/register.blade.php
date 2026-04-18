<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Registration | Bootstrap Security</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md p-8 bg-gray-800 rounded-3xl shadow-2xl border border-gray-700 relative overflow-hidden">
        <!-- Glow effect -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-600/20 rounded-full blur-3xl"></div>

        <div class="relative text-center mb-8">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-blue-600/10 rounded-2xl border border-blue-500/20 flex items-center justify-center shadow-lg shadow-blue-500/10">
                    <i class="fa-solid fa-shield-halved text-4xl text-blue-500"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">Bootstrap Setup</h2>
            <p class="mt-2 text-gray-400 text-sm italic">Create the system owner account</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-xl text-xs mb-6 backdrop-blur-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('super_admin.register.submit') }}" method="POST" class="space-y-5 relative">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-sm"
                            placeholder="Owner Name">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-sm"
                            placeholder="owner@example.com">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2 ml-1">Password</label>
                        <input id="password" name="password" type="password" required
                            class="w-full px-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2 ml-1">Confirm</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full px-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3">
                    <span>Create Master Account</span>
                    <i class="fa-solid fa-arrow-right text-xs opacity-50"></i>
                </button>
            </div>

            <p class="text-center text-[10px] text-gray-500 uppercase tracking-tighter">
                <i class="fa-solid fa-lock mr-1"></i> Public registration will be locked after this step
            </p>
        </form>
    </div>

</body>

</html>