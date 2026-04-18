@extends('layouts.teacher')

@section('content')
<style>
    /* Custom Animations */
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-enter-left {
        animation: fadeInLeft 0.6s ease-out forwards;
    }

    .animate-enter-down {
        animation: fadeInDown 0.6s ease-out 0.2s forwards;
        opacity: 0;
    }

    /* Delay 0.2s */
    .animate-enter-up {
        animation: fadeInUp 0.6s ease-out 0.4s forwards;
        opacity: 0;
    }

    /* Delay 0.4s */

    /* Hover Effects */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .profile-glow {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
    }

    .profile-glow:hover {
        transform: scale(1.05);
    }

    /* Custom Input Styles */
    .input-modern {
        background: transparent;
        border-bottom: 2px solid #e2e8f0;
        transition: all 0.3s;
    }

    .input-modern:focus {
        border-bottom-color: #3b82f6;
        background: rgba(59, 130, 246, 0.02);
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Toast Notification -->
    @if (session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm animate-enter-down" x-data="{ show: true }" x-show="show">
        <div class="flex">
            <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-green-500"></i></div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- CARD #1: The Identity Card (Left Col - 4 cols) -->
        <div class="lg:col-span-4 animate-enter-left">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover-lift h-full relative">
                <!-- Header Gradient -->
                <div class="h-32 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 relative">
                    <div class="absolute top-4 right-4 text-white/20 hover:text-white transition-colors cursor-pointer">
                        <i class="fa-solid fa-share-nodes text-xl"></i>
                    </div>
                </div>

                <div class="px-6 pb-8 text-center relative">
                    <!-- Profile Image -->
                    <div class="-mt-16 mb-4 relative inline-block">
                        @if($teacher->image)
                        <img src="{{ asset('storage/' . $teacher->image) }}"
                            alt="{{ $teacher->name }}"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white bg-white profile-glow transition-transform duration-300">
                        @else
                        <div class="w-32 h-32 rounded-full bg-slate-100 flex items-center justify-center border-4 border-white profile-glow transition-transform duration-300 text-slate-400 text-5xl">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        @endif
                        <div class="absolute bottom-2 right-1 bg-emerald-500 w-6 h-6 rounded-full border-4 border-white" title="Online"></div>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $teacher->name }}</h2>
                    <div class="mt-2 mb-6">
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $teacher->subject ?? 'Educator' }}
                        </span>
                    </div>

                    <p class="text-slate-500 text-sm mb-6 px-4 leading-relaxed">
                        Dedicated teacher committed to fostering a positive learning environment and student success.
                        <br>
                        <span class="text-xs font-medium text-slate-400 mt-2 block">Member Since: {{ $teacher->created_at->format('M Y') }}</span>
                    </p>

                    <!-- Social/Contact Placeholders -->
                    <div class="flex justify-center gap-4 pt-6 border-t border-slate-100">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-all">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-all">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="mailto:{{ $teacher->email }}" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-all">
                            <i class="fa-solid fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN WRAPPER -->
        <div class="lg:col-span-8 space-y-8">

            <!-- CARD #2: The Stats Display (Right Top) -->
            <div class="animate-enter-down">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stat A: Classes -->
                    <div class="bg-white rounded-xl shadow-md border border-slate-100 p-6 flex items-center gap-4 hover-lift">
                        <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Classes</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ $teacher->schoolClasses->count() }}</h3>
                        </div>
                    </div>

                    <!-- Stat B: Students (Approximate/Mock or Count if accessible) -->
                    <div class="bg-white rounded-xl shadow-md border border-slate-100 p-6 flex items-center gap-4 hover-lift">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Students</p>
                            <!-- Fetching strict count might be heavy, calculating simply: -->
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{-- Simple logic to count unique students across classes --}}
                                {{ \App\Models\Student::whereIn('class_id', $teacher->schoolClasses->pluck('id'))->count() }}
                            </h3>
                        </div>
                    </div>

                    <!-- Stat C: Experience / Tenure -->
                    <div class="bg-white rounded-xl shadow-md border border-slate-100 p-6 flex items-center gap-4 hover-lift">
                        <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Experience</p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $teacher->created_at->diffInYears() > 1 ? $teacher->created_at->diffInYears() . ' Years' : (($teacher->created_at->diffInMonths() > 0) ? $teacher->created_at->diffInMonths().' Months' : 'New') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD #3: The Control Center (Right Bottom) -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden animate-enter-up flex flex-col h-auto" x-data="{ activeTab: 'edit_profile' }">

                <!-- Modern Tabs Header -->
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-indigo-500"></i> Settings & Profile
                    </h3>
                    <div class="bg-slate-200/50 p-1 rounded-lg inline-flex">
                        <button @click="activeTab = 'edit_profile'"
                            :class="{ 'bg-white text-indigo-600 shadow-sm': activeTab === 'edit_profile', 'text-slate-500 hover:text-slate-700': activeTab !== 'edit_profile' }"
                            class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all duration-200">
                            Edit Profile
                        </button>
                        <button @click="activeTab = 'security'"
                            :class="{ 'bg-white text-indigo-600 shadow-sm': activeTab === 'security', 'text-slate-500 hover:text-slate-700': activeTab !== 'security' }"
                            class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all duration-200">
                            Security
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="p-8">
                    <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- TAB 1: Edit Profile -->
                        <div x-show="activeTab === 'edit_profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <!-- Name -->
                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none" required>
                                </div>

                                <!-- Phone -->
                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $teacher->phone ?? '') }}" placeholder="+1 234 567 890" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none">
                                </div>

                                <!-- Email (Full Width) -->
                                <div class="col-span-2 relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none" required>
                                </div>

                                <!-- Address (Full Width) -->
                                <div class="col-span-2 relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Address</label>
                                    <input type="text" name="address" value="{{ old('address', $teacher->address ?? '') }}" placeholder="Enter residential address" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none">
                                </div>

                                <!-- Subject -->
                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Subject</label>
                                    <input type="text" name="subject" value="{{ old('subject', $teacher->subject) }}" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none">
                                </div>

                                <!-- Image Upload -->
                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-2">Update Photo</label>
                                    <label class="inline-flex items-center px-4 py-2 bg-slate-50 text-slate-600 rounded-lg cursor-pointer border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all text-sm font-medium">
                                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Choose File
                                        <input type="file" name="image" class="hidden" accept="image/*" onchange="this.nextElementSibling.textContent = this.files[0].name.substring(0, 15) + '...'">
                                        <span class="ml-2 text-xs text-slate-400"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: Security -->
                        <div x-show="activeTab === 'security'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="max-w-md mx-auto space-y-6">
                                <div class="text-center mb-6">
                                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-2 text-xl">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <h4 class="text-slate-800 font-bold">Change Password</h4>
                                    <p class="text-slate-500 text-sm">Keep your account secure with a strong password.</p>
                                </div>

                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">New Password</label>
                                    <input type="password" name="password" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none" placeholder="••••••••">
                                </div>

                                <div class="relative group">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full py-2 input-modern text-slate-700 font-medium focus:outline-none" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-1 transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-check"></i> Save Updates
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection