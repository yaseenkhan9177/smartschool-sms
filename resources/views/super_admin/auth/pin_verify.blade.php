<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security PIN Verification | Own Education</title>
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
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-2xl shadow-orange-600/30 mb-5">
                <i class="fa-solid fa-shield-halved text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Security Verification</h1>
            <p class="text-slate-400 mt-2 text-sm">Enter today's dynamic PIN to unlock Super Admin registration.</p>
        </div>

        {{-- Alert: Warning (redirect from expired session) --}}
        @if(session('warning'))
        <div class="mb-5 flex items-start gap-3 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-sm">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        {{-- Card --}}
        <div class="bg-slate-800/60 backdrop-blur border border-slate-700/60 rounded-2xl shadow-2xl p-8">

            {{-- Today's date hint --}}
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600/10 border border-blue-500/20 mb-6">
                <i class="fa-regular fa-calendar-days text-blue-400"></i>
                <div>
                    <p class="text-blue-300 text-xs font-semibold uppercase tracking-wide">Today's Date Hint</p>
                    <p class="text-slate-200 text-sm font-mono">
                        99
                    </p>
                </div>
            </div>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-5 flex items-start gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/25 text-red-300 text-sm">
                <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
                <span>{{ $errors->first('pin') }}</span>
            </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('super_admin.pin.verify') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="pin" class="block text-sm font-medium text-slate-300 mb-2">
                        6-Digit Security PIN
                    </label>
                    <input
                        type="text"
                        id="pin"
                        name="pin"
                        maxlength="6"
                        autocomplete="off"
                        inputmode="numeric"
                        pattern="\d{6}"
                        placeholder="e.g. 110399"
                        autofocus
                        class="w-full bg-slate-900/70 border border-slate-600 text-white text-center text-2xl font-mono tracking-[0.5em] rounded-xl px-4 py-4 placeholder:text-slate-600 placeholder:text-base placeholder:tracking-normal focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/30 transition-all"
                        value="{{ old('pin') }}">
                    <p class="mt-2 text-xs text-slate-500 text-center">
                        <i class="fa-solid fa-clock mr-1"></i>
                        Verified session is valid for <span class="text-amber-400 font-semibold">5 minutes</span> only.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-base shadow-lg shadow-orange-600/30 hover:from-amber-400 hover:to-orange-500 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-unlock"></i>
                    Verify PIN & Continue
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-700/50 text-center">
                <a href="{{ route('super_admin.settings') }}" class="text-slate-500 text-sm hover:text-slate-300 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                    Back to Settings
                </a>
            </div>
        </div>

        {{-- Security note --}}
        <p class="text-center text-slate-600 text-xs mt-6">
            <i class="fa-solid fa-lock mr-1"></i>
            Max 5 attempts per minute · Rate limited per IP
        </p>

    </div>

</body>

</html>