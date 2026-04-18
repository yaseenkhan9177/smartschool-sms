<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal | Own Education</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        [x-cloak] {
            display: none !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            header {
                display: none !important;
            }

            footer {
                display: none !important;
            }
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">
</head>

<body class="bg-[#f4f6f9] font-sans antialiased min-h-screen flex flex-col" x-data="{ showTeachersModal: false, showLeaveModal: false, showComplaintModal: false }">

    <!-- LAYER 1: The "Hero" Identity Card -->
    <header class="hero-gradient text-white pt-6 pb-16 px-4 sm:px-6 lg:px-8 shadow-xl relative z-10">
        <div class="max-w-7xl mx-auto">

            @php
            $parentUser = $currentUser;
            // $adminUser and $school are already provided by AppServiceProvider view composer
            @endphp

            <!-- School Brand Row -->
            <div class="flex items-center gap-3 mb-6 bg-white/10 w-fit px-4 py-2.5 rounded-2xl backdrop-blur-sm border border-white/10 shadow-sm">
                @if($schoolLogo)  t  o
                <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="h-8 w-8 rounded-full object-cover bg-white shadow-sm ring-2 ring-white/20">
                @else
                <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center shadow-sm ring-2 ring-white/20">
                    <i class="fa-solid fa-school text-sm"></i>
                </div>
                @endif
                <div class="flex flex-col">
                    <span class="text-sm font-bold tracking-tight leading-none">{{ $schoolName }}</span>
                    <span class="text-[9px] text-indigo-200 uppercase tracking-wider font-semibold mt-0.5">Parent Portal</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-6">

                @if(isset($currentStudent) && $currentStudent)
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <!-- Left Side: Large circular Profile Photo -->
                    <div class="relative items-center justify-center flex">
                        @if($currentStudent->profile_image)
                        <img src="{{ asset('uploads/students/' . $currentStudent->profile_image) }}" class="w-24 h-24 rounded-full border-4 border-white/20 shadow-lg object-cover">
                        @else
                        <div class="w-24 h-24 rounded-full bg-white/10 border-4 border-white/20 flex items-center justify-center text-3xl font-bold shadow-lg">
                            {{ substr($currentStudent->name, 0, 1) }}
                        </div>
                        @endif
                        <!-- Green "Active" dot -->
                        <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-400 border-2 border-indigo-600 rounded-full" title="Active"></span>
                    </div>

                    <!-- Middle: Big Name & Details -->
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-bold tracking-tight">{{ $currentStudent->name }}</h1>
                        <p class="text-indigo-100 text-lg mt-1 font-medium">Class {{ $currentStudent->schoolClass->name ?? 'N/A' }} | Roll No: {{ $currentStudent->roll_number }}</p>
                    </div>
                </div>

                <!-- Right Side: "Switch Child" and "Log Out" -->
                <div class="flex items-center gap-3">
                    @if(isset($students) && $students->count() > 1)
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10">
                            <span class="text-sm font-medium">Switch Child</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl overflow-hidden py-1 z-50 text-gray-800" x-cloak>
                            @foreach($students as $s)
                            <a href="{{ request()->fullUrlWithQuery(['student_id' => $s->id]) }}" class="block px-4 py-3 hover:bg-gray-50 {{ $s->id === $currentStudent->id ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                                {{ $s->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <a href="{{ route('parent.dashboard') }}" class="bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10" title="Dashboard">
                        <i class="fa-solid fa-home"></i>
                    </a>

                    <a href="{{ route('logout') }}" class="bg-white/10 hover:bg-red-500/80 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10" title="Logout">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                </div>
                @else
                <div class="text-center w-full">
                    <h1 class="text-2xl font-bold">Parent Portal</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logout') }}" class="bg-white/10 hover:bg-red-500/80 px-4 py-2.5 rounded-xl transition backdrop-blur-sm border border-white/10" title="Logout">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                </div>
                @endif

            </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 flex-1 w-full pb-12 pt-4 relative z-20">
        @yield('content')
    </main>

    <footer class="mt-auto py-6 text-center text-xs text-gray-400 no-print">
        &copy; {{ date('Y') }} Own Education Systems. All rights reserved.
    </footer>

    @include('partials.pwa-scripts')
</body>

</html>