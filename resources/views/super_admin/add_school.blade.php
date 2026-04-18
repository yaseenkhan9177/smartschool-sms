@extends('layouts.admin')

@section('title', 'Add New School')

@section('content')
<div class="px-6 py-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('super_admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-700 mb-2 inline-block">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold text-gray-900">Add New School</h1>
            <p class="text-gray-500">Register a new school and create a Principal account.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
        <p class="font-bold">Please correct the following errors:</p>
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        <form action="{{ route('super_admin.store_school') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- School Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">School Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                        <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="e.g. Springfield High">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address (Optional)</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="City, Country">
                    </div>
                </div>
            </div>

            <!-- Principal Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Principal (Admin) Account</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Principal Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Full Name">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="+1 234 567 890">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address (Login ID)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="admin@school.com">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="flex space-x-2">
                            <input type="text" name="password" id="password" required value="{{ old('password') }}"
                                class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-gray-50"
                                placeholder="Secure Password">
                            <button type="button" onclick="generatePassword()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm transition-colors">
                                Generate
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Click 'Generate' to create a strong password.</p>
                    </div>
                </div>
            </div>

            <!-- Subscription Plan -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Subscription Plan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="plan_duration" class="block text-sm font-medium text-gray-700 mb-1">Initial License Duration</label>
                        <select name="plan_duration" id="plan_duration" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="1" {{ old('plan_duration') == '1' ? 'selected' : '' }}>1 Month Trial</option>
                            <option value="6" {{ old('plan_duration') == '6' ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ old('plan_duration', '12') == '12' ? 'selected' : '' }}>1 Year</option>
                            <option value="24" {{ old('plan_duration') == '24' ? 'selected' : '' }}>2 Years</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Create School Used & Activate License
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        document.getElementById("password").value = retVal;
    }
</script>
@endsection