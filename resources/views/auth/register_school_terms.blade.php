<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Own Education</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-3xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="px-8 py-6 bg-slate-900 border-b border-slate-700">
            <h1 class="text-3xl font-bold text-white text-center">Platform Agreement</h1>
            <p class="text-slate-400 text-center text-sm mt-2">Please read and accept the terms to proceed.</p>
        </div>

        <div class="p-8">
            <div class="prose max-w-none h-96 overflow-y-auto mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-200 text-gray-700 leading-relaxed text-sm">
                {!! nl2br(e($terms->content)) !!}
            </div>

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                            <span class="block">{{ $error }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('school.register.terms.accept') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="terms_version" value="{{ $terms->version }}">

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="agree" name="agree" type="checkbox" required class="w-5 h-5 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                    </div>
                    <label for="agree" class="ml-3 text-sm font-medium text-gray-900">
                        I adhere to the <span class="font-bold">platform terms & conditions</span> including system usage rules, data responsibility, and termination rights.
                    </label>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-xl text-lg px-10 py-3 text-center transition-colors shadow-lg">
                        Agree & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>