@extends('layouts.super_admin')

@section('title', 'Admin Settings')

@section('content')
<div class="px-6 py-6 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Settings</h1>
        <p class="text-gray-500">Manage super admin accounts and system configurations.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 text-sm">
        <div class="flex items-center gap-3 mb-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span class="font-bold">Please check the following errors:</span>
        </div>
        <ul class="list-disc list-inside ml-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create New Admin Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Add New Super Admin</h2>

                {{-- PIN Status Banner --}}
                @php
                $pinVerified = session('super_admin_pin_verified');
                $pinTime = session('super_admin_pin_time');
                $pinActive = $pinVerified && $pinTime && now()->diffInMinutes($pinTime) <= 5;
                    @endphp

                    @if($pinActive)
                    {{-- PIN is verified and still within 5 minutes --}}
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-700 text-sm">
                    <i class="fa-solid fa-shield-check text-emerald-500"></i>
                    <div>
                        <p class="font-semibold">PIN Verified ✅</p>
                        <p class="text-xs text-emerald-600 mt-0.5">Your session is active. You can submit the form now.</p>
                    </div>
            </div>
            @else
            {{-- PIN not verified or expired --}}
            <div class="mb-5 px-4 py-4 rounded-xl bg-amber-500/10 border border-amber-500/25">
                <div class="flex items-center gap-3 text-amber-700 mb-3">
                    <i class="fa-solid fa-shield-halved text-amber-500 text-lg"></i>
                    <div>
                        <p class="font-semibold text-sm">Security PIN Required 🔒</p>
                        <p class="text-xs text-amber-600 mt-0.5">Verify today's PIN to unlock this form.</p>
                    </div>
                </div>
                <a href="{{ route('super_admin.pin.show') }}"
                    class="block w-full text-center py-2.5 rounded-xl bg-amber-500 text-white font-semibold text-sm hover:bg-amber-600 transition-colors shadow shadow-amber-500/30">
                    <i class="fa-solid fa-lock-open mr-1"></i>
                    Verify Security PIN
                </a>
            </div>
            @endif

            <form action="{{ route('super_admin.settings.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm {{ !$pinActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                        placeholder="John Doe" {{ !$pinActive ? 'disabled' : '' }}>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" required
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm {{ !$pinActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                        placeholder="john@example.com" {{ !$pinActive ? 'disabled' : '' }}>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm {{ !$pinActive ? 'opacity-50 cursor-not-allowed' : '' }}"
                        placeholder="••••••••" {{ !$pinActive ? 'disabled' : '' }}>
                </div>

                <button type="submit"
                    class="w-full py-2.5 rounded-xl font-semibold transition-colors shadow-lg {{ $pinActive ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-600/20' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
                    {{ !$pinActive ? 'disabled' : '' }}>
                    {{ $pinActive ? 'Create Admin' : '🔒 Verify PIN First' }}
                </button>
            </form>
        </div>
    </div>


    <!-- List of Admins -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Current Super Admins</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Admin</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Joined Date</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($superAdmins as $admin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $admin->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $admin->email }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($admin->status === 'active')
                                <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">Active</span>
                                @else
                                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ optional($admin->created_at)->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($admin->status === 'pending')
                                <form action="{{ route('super_admin.settings.approve', $admin->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 transition-colors" title="Approve Super Admin">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('super_admin.settings.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Super Admin?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete Super Admin">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection