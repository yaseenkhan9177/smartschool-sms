<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Registration - Own Education</title>
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

<body class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="px-8 py-8 bg-slate-900 border-b border-slate-700 text-center">
            <h1 class="text-3xl font-bold text-white">School Registration</h1>
            <p class="text-slate-400 mt-2">Enter your institution's details to get started.</p>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">School Registration</h2>

        <div class="p-10">
            <form action="{{ route('school.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Details -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-slate-800 border-b pb-2 mb-4">Institution Details</h3>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">School Name</label>
                            <input type="text" name="school_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="e.g. Springfield High" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">School Logo</label>
                            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">PNG, JPG or GIF (MAX. 2MB).</p>
                            @error('logo')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">City</label>
                            <input type="text" name="city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Full Address</label>
                            <textarea name="address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Approx. No. of Students (Optional)</label>
                            <input type="number" name="student_count" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                    </div>

                    <!-- Owner Details -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-slate-800 border-b pb-2 mb-4">Owner / Contact Person</h3>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Owner / Principal Name</label>
                            <input type="text" name="owner_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Official Email</label>
                            <input type="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="admin@school.com" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                            <input type="tel" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl text-lg px-10 py-4 text-center transition-colors shadow-lg transform hover:-translate-y-1">
                        Submit Registration Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>