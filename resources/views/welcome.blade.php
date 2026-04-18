<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Own Education</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/img/logo-round.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS -->
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
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(30, 41, 59, 0.8);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        /* Text Gradients */
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Parallax Backgrounds */
        .parallax-section {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Swiper Customization */
        .swiper {
            width: 100%;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 400px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            background-color: #1e293b;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-pagination-bullet-active {
            background-color: #3b82f6 !important;
        }

        /* Blob Animation */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Marquee Animation */
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            animation: marquee 25s linear infinite;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-300 font-sans antialiased selection:bg-blue-500 selection:text-white"
    x-data="{ 
          mobileMenuOpen: false, 
          scrollY: 0,
          stats: { students: 0, teachers: 0, success: 0, support: 0 },
          targetStats: { students: 1000, teachers: 50, success: 100, support: 24 }
      }"
    @scroll.window="scrollY = window.scrollY">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300"
        :class="scrollY > 50 ? 'py-2 shadow-lg' : 'py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center p-0.5 group hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Own Education" class="w-full h-full rounded-full object-cover">
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">Own <span class="text-blue-400">Education</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-sm font-medium text-slate-300 hover:text-white transition-colors hover:scale-105 transform">Home</a>
                    <a href="#features" class="text-sm font-medium text-slate-300 hover:text-white transition-colors hover:scale-105 transform">Features</a>
                    <a href="#gallery" class="text-sm font-medium text-slate-300 hover:text-white transition-colors hover:scale-105 transform">Gallery</a>
                    <a href="#stats" class="text-sm font-medium text-slate-300 hover:text-white transition-colors hover:scale-105 transform">Impact</a>

                    <div class="flex items-center gap-4 ml-4 pl-4 border-l border-slate-700">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-500 shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 hover:shadow-blue-600/40 active:scale-95">
                            Login
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-300 hover:text-white p-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition
            class="md:hidden glass-nav border-t border-slate-800 absolute w-full">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#home" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800/50">Home</a>
                <a href="#features" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800/50">Features</a>
                <a href="#gallery" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800/50">Gallery</a>
                <a href="#stats" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800/50">Impact</a>
                <div class="pt-4 mt-4 border-t border-slate-800 grid grid-cols-2 gap-4">
                    <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-500">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Advanced Parallax) -->
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">

        <!-- Parallax Background Layers -->
        <div class="absolute inset-0 z-0 scale-110"
            :style="'transform: translateY(' + (scrollY * 0.5) + 'px);'">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop"
                class="w-full h-full object-cover opacity-40" alt="University Background">
        </div>

        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/50 to-slate-900 z-0"></div>

        <!-- Floating Elements Parallax -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-blob"
                :style="'transform: translateY(' + (scrollY * 0.2) + 'px)'"></div>
            <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-blob animation-delay-2000"
                :style="'transform: translateY(' + (scrollY * -0.1) + 'px)'"></div>
            <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-indigo-600/20 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-blob animation-delay-4000"
                :style="'transform: translateY(' + (scrollY * 0.3) + 'px)'"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20"
            data-aos="zoom-out" data-aos-duration="1200">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-medium mb-8 backdrop-blur-sm hover:bg-blue-500/20 transition-colors cursor-default">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Admissions Open for {{ date('Y') }}
            </div>

            <!-- New Register Button -->
            <div class="mb-8">
                <a href="{{ route('school.register.terms') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg hover:shadow-lg hover:from-blue-500 hover:to-purple-500 transition-all transform hover:-translate-y-1">
                    <i class="fa-solid fa-school"></i>
                    Register Your School
                </a>


            </div>

            <h1 class="text-5xl lg:text-8xl font-bold tracking-tight text-white mb-4 leading-tight drop-shadow-lg">
                Own <br>
                <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">Education</span>
            </h1>

            <p class="mt-4 text-xl lg:text-2xl text-slate-300 max-w-3xl mx-auto mb-10 leading-relaxed font-light drop-shadow-md">
                The Complete School Management System for Pakistan.
            </p>


        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
            <a href="#features" class="text-slate-400 hover:text-white transition-colors p-2">
                <i class="fa-solid fa-chevron-down text-xl"></i>
            </a>
        </div>
    </section>

    <!-- Digital Notice Board / News Ticker -->
    <div class="bg-blue-900/50 backdrop-blur-md border-y border-blue-500/20 py-3 overflow-hidden relative z-20">
        <div class="max-w-7xl mx-auto flex items-center relative">
            <div class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded absolute left-4 z-20 shadow-lg uppercase tracking-wider">Latest News</div>
            <div class="animate-marquee whitespace-nowrap flex gap-12 text-slate-300 text-sm font-medium items-center w-full pl-32">
                <span class="flex items-center gap-2"><i class="fa-solid fa-snowflake text-blue-400"></i> Winter Break begins on December 24th</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-file-contract text-yellow-400"></i> Final Exam Schedule has been released</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-trophy text-amber-400"></i> Annual Sports Day postponed to next Friday</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-star text-purple-400"></i> Admissions Open for 2026 - Apply Now!</span>
                <!-- Duplicate for loop -->
                <span class="flex items-center gap-2"><i class="fa-solid fa-snowflake text-blue-400"></i> Winter Break begins on December 24th</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-file-contract text-yellow-400"></i> Final Exam Schedule has been released</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-trophy text-amber-400"></i> Annual Sports Day postponed to next Friday</span>
            </div>
        </div>
    </div>

    <!-- 3D Slider Section -->
    <section id="gallery" class="py-32 bg-slate-900 relative overflow-hidden" data-aos="zoom-in" data-aos-duration="1000">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-blue-900/10 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 text-center" data-aos="fade-down">
            <h2 class="text-4xl lg:text-6xl font-bold text-white mb-6">Highlights & <span class="text-gradient">Campus Life</span></h2>
            <p class="text-slate-400 text-xl max-w-2xl mx-auto">Explore the vibrant community and state-of-the-art facilities that make Own Education unique.</p>
        </div>

        <!-- Swiper -->
        <div class="swiper mySwiper !pb-14 !pt-10">
            <div class="swiper-wrapper">
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=2071&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Modern Classrooms</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Interactive learning spaces designed for collaboration.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Digital Library</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Access to thousands of digital resources anywhere.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?q=80&w=2070&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Sports Complex</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">World-class facilities for athletic excellence.</p>
                    </div>
                </div>
                <!-- More slides... -->
                <!-- More slides... -->
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1564981797816-1043664bf78d?q=80&w=1974&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Science Labs</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Advanced equipment for hands-on experimentation.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1577896238808-929628fe260e?q=80&w=1999&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Cultural Events</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Celebrating diversity through arts and culture.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=2070&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Innovation Hub</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Where ideas turn into reality.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Global Alumni</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Connect with leaders worldwide.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1588072432704-5b721e06fa2e?q=80&w=2070&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Green Campus</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Sustainable living and learning.</p>
                    </div>
                </div>
                <div class="swiper-slide glass group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?q=80&w=2074&auto=format&fit=crop" class="transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-slate-900/80 backdrop-blur-sm border-t border-white/10 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white font-bold text-xl mb-1">Research Works</h3>
                        <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">Pioneering discoveries every day.</p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Features Section with Scroll Animations -->
    <section id="features" class="py-32 bg-slate-800/30 relative" data-aos="zoom-in" data-aos-duration="1000">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ tab: 'parents' }">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-4xl lg:text-6xl font-bold text-white mb-6">Features for <span class="text-gradient">Everyone</span></h2>
                <p class="text-slate-400 text-xl mb-10">Tailored tools for every member of our educational community.</p>

                <!-- Role Switcher Tabs -->
                <div class="inline-flex bg-slate-900/50 p-1 rounded-2xl border border-slate-700 backdrop-blur-sm">
                    <button @click="tab = 'parents'" :class="tab === 'parents' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'" class="px-6 py-2.5 rounded-xl font-medium transition-all duration-300">
                        <i class="fa-solid fa-users mr-2"></i> Parents
                    </button>
                    <button @click="tab = 'students'" :class="tab === 'students' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'" class="px-6 py-2.5 rounded-xl font-medium transition-all duration-300">
                        <i class="fa-solid fa-graduation-cap mr-2"></i> Students
                    </button>
                    <button @click="tab = 'teachers'" :class="tab === 'teachers' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'" class="px-6 py-2.5 rounded-xl font-medium transition-all duration-300">
                        <i class="fa-solid fa-chalkboard-user mr-2"></i> Teachers
                    </button>
                </div>
            </div>

            <!-- Parent Features -->
            <div x-show="tab === 'parents'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid md:grid-cols-3 gap-8">
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center text-green-400 text-2xl mb-6">
                        <i class="fa-solid fa-child-reaching"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Track Progress</h3>
                    <p class="text-slate-400 leading-relaxed">Real-time updates on your child's grades, attendance, and behavioral performance.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400 text-2xl mb-6">
                        <i class="fa-regular fa-credit-card"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Fee Payments</h3>
                    <p class="text-slate-400 leading-relaxed">Secure online fee payments and instant receipt generation.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 text-2xl mb-6">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Direct Communication</h3>
                    <p class="text-slate-400 leading-relaxed">Chat directly with teachers and school administration.</p>
                </div>
            </div>

            <!-- Student Features -->
            <div x-show="tab === 'students'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid md:grid-cols-3 gap-8" style="display: none;">
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 text-2xl mb-6">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Digital Resources</h3>
                    <p class="text-slate-400 leading-relaxed">Access study materials, lecture notes, and assignments anytime.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-pink-500/20 flex items-center justify-center text-pink-400 text-2xl mb-6">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Smart Timetable</h3>
                    <p class="text-slate-400 leading-relaxed">Personalized class schedules and exam dates at a glance.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 text-2xl mb-6">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Performance Analytics</h3>
                    <p class="text-slate-400 leading-relaxed">Visualize your academic growth with intuitive charts.</p>
                </div>
            </div>

            <!-- Teacher Features -->
            <div x-show="tab === 'teachers'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid md:grid-cols-3 gap-8" style="display: none;">
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-red-500/20 flex items-center justify-center text-red-400 text-2xl mb-6">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Easy Grading</h3>
                    <p class="text-slate-400 leading-relaxed">Streamlined grade entry and automated report card generation.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-yellow-500/20 flex items-center justify-center text-yellow-400 text-2xl mb-6">
                        <i class="fa-solid fa-hands-holding-child"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Attendance Management</h3>
                    <p class="text-slate-400 leading-relaxed">Quick and accurate daily attendance marking.</p>
                </div>
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-2xl mb-6">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Class Announcements</h3>
                    <p class="text-slate-400 leading-relaxed">Send notifications to students and parents instantly.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Testimonials Slider -->
    <section id="reviews" class="py-24 bg-slate-900 border-t border-slate-800" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-12 text-center">Success <span class="text-gradient-gold">Stories</span></h2>

            <div class="swiper testimonialSwiper pb-12">
                <div class="swiper-wrapper">
                    <div class="swiper-slide !w-full md:!w-[400px] !h-auto !bg-transparent p-4">
                        <div class="glass-card p-8 rounded-3xl h-full flex flex-col relative">
                            <i class="fa-solid fa-quote-left text-4xl text-blue-500/20 absolute top-6 left-6"></i>
                            <p class="text-slate-300 mb-6 relative z-10 pt-4 leading-relaxed">"The dedicated faculty and state-of-the-art facilities have truly transformed my daughter's learning experience. She loves going to school every day!"</p>
                            <div class="mt-auto flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-12 h-12 rounded-full border-2 border-blue-500" alt="Parent">
                                <div>
                                    <h4 class="text-white font-bold">Sarah Johnson</h4>
                                    <p class="text-blue-400 text-xs">Parent, Grade 5</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide !w-full md:!w-[400px] !h-auto !bg-transparent p-4">
                        <div class="glass-card p-8 rounded-3xl h-full flex flex-col relative">
                            <i class="fa-solid fa-quote-left text-4xl text-purple-500/20 absolute top-6 left-6"></i>
                            <p class="text-slate-300 mb-6 relative z-10 pt-4 leading-relaxed">"Own Education provided me with the foundation I needed to excel in university. The personalized attention from teachers was invaluable."</p>
                            <div class="mt-auto flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-12 h-12 rounded-full border-2 border-purple-500" alt="Alumni">
                                <div>
                                    <h4 class="text-white font-bold">Michael Chen</h4>
                                    <p class="text-purple-400 text-xs">Alumni, Class of 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide !w-full md:!w-[400px] !h-auto !bg-transparent p-4">
                        <div class="glass-card p-8 rounded-3xl h-full flex flex-col relative">
                            <i class="fa-solid fa-quote-left text-4xl text-emerald-500/20 absolute top-6 left-6"></i>
                            <p class="text-slate-300 mb-6 relative z-10 pt-4 leading-relaxed">"The sports program here is exceptional. I've learned so much about teamwork and discipline both on and off the field."</p>
                            <div class="mt-auto flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/men/85.jpg" class="w-12 h-12 rounded-full border-2 border-emerald-500" alt="Student">
                                <div>
                                    <h4 class="text-white font-bold">James Wilson</h4>
                                    <p class="text-emerald-400 text-xs">Student, Grade 11</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination !bottom-0"></div>
            </div>
        </div>
    </section>

    <!-- Admissions Timeline -->
    <section id="admissions" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-6xl font-bold text-white mb-6">Admissions <span class="text-gradient">Process</span></h2>
                <p class="text-slate-400 text-xl">Your journey to excellence starts here.</p>
            </div>

            <!-- Steps -->
            <div class="relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-slate-700 -translate-y-1/2 z-0"></div>

                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="relative z-10 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-slate-900 border-4 border-slate-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:border-blue-500 transition-colors duration-300 shadow-xl">
                            <i class="fa-solid fa-file-pen text-2xl text-slate-400 group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="text-center bg-slate-800/50 p-6 rounded-2xl backdrop-blur-sm border border-slate-700 hover:border-blue-500/30 transition-all hover:-translate-y-2">
                            <h3 class="text-white font-bold text-xl mb-2">1. Apply Online</h3>
                            <p class="text-slate-400 text-sm">Submit the student's details and previous academic records via our portal.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-slate-900 border-4 border-slate-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:border-purple-500 transition-colors duration-300 shadow-xl">
                            <i class="fa-solid fa-pen-ruler text-2xl text-slate-400 group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="text-center bg-slate-800/50 p-6 rounded-2xl backdrop-blur-sm border border-slate-700 hover:border-purple-500/30 transition-all hover:-translate-y-2">
                            <h3 class="text-white font-bold text-xl mb-2">2. Entrance Test</h3>
                            <p class="text-slate-400 text-sm">Students take a basic aptitude test to assess their current proficiency.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="bg-slate-900 border-4 border-slate-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:border-emerald-500 transition-colors duration-300 shadow-xl">
                            <i class="fa-solid fa-handshake text-2xl text-slate-400 group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="text-center bg-slate-800/50 p-6 rounded-2xl backdrop-blur-sm border border-slate-700 hover:border-emerald-500/30 transition-all hover:-translate-y-2">
                            <h3 class="text-white font-bold text-xl mb-2">3. Interview</h3>
                            <p class="text-slate-400 text-sm">A friendly interaction with parents and student to understand goals.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative z-10 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="bg-slate-900 border-4 border-slate-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:border-amber-500 transition-colors duration-300 shadow-xl">
                            <i class="fa-solid fa-check text-2xl text-slate-400 group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="text-center bg-slate-800/50 p-6 rounded-2xl backdrop-blur-sm border border-slate-700 hover:border-amber-500/30 transition-all hover:-translate-y-2">
                            <h3 class="text-white font-bold text-xl mb-2">4. Admission</h3>
                            <p class="text-slate-400 text-sm">Complete the fee payment and documentation to secure the seat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-slate-900 border-t border-slate-800" data-aos="fade-up" x-data="{ annual: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">Flexible Plans for <span class="text-gradient">Every Institution</span></h2>
                <p class="text-slate-400 text-xl mb-8">Choose the perfect plan that scales with your growth.</p>

                <!-- Toggle Switch -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-lg font-medium" :class="!annual ? 'text-white' : 'text-slate-500'">Monthly</span>
                    <button @click="annual = !annual" class="relative w-16 h-8 rounded-full bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900" :class="annual ? 'bg-blue-600' : 'bg-slate-700'">
                        <div class="absolute top-1 left-1 w-6 h-6 rounded-full bg-white transition-transform duration-300" :style="annual ? 'transform: translateX(32px);' : 'transform: translateX(0);'"></div>
                    </button>
                    <span class="text-lg font-medium" :class="annual ? 'text-white' : 'text-slate-500'">Yearly <span class="text-xs text-green-400 font-bold ml-1 bg-green-500/10 px-2 py-0.5 rounded-full border border-green-500/20">Save 20%</span></span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Basic Plan -->
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden flex flex-col hover:border-blue-500/30 transition-all duration-300">
                    <h3 class="text-white font-bold text-2xl mb-2">Basic</h3>
                    <p class="text-slate-400 text-sm mb-6">Small Schools</p>
                    <div class="mb-8">
                        <span class="text-4xl font-bold text-white" x-text="annual ? '$490' : '$49'">$49</span>
                        <span class="text-slate-400" x-text="annual ? '/ year' : '/ month'">/ month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-300 flex-1">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Up to 200 Students</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Student Portal</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Attendance Tracking</li>
                        <li class="flex items-center gap-3 opacity-50"><i class="fa-solid fa-xmark text-slate-500"></i> Online Exams</li>
                    </ul>
                    <a href="#" class="block w-full py-3 rounded-xl border border-slate-600 text-white text-center font-bold hover:bg-slate-800 transition-colors">Start Free Trial</a>
                </div>

                <!-- Standard Plan -->
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden flex flex-col border-blue-500 shadow-lg shadow-blue-500/10 transform scale-105 z-10">
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">POPULAR</div>
                    <h3 class="text-white font-bold text-2xl mb-2">Standard</h3>
                    <p class="text-blue-200 text-sm mb-6">Growing Institutions</p>
                    <div class="mb-8">
                        <span class="text-4xl font-bold text-white" x-text="annual ? '$1,490' : '$149'">$149</span>
                        <span class="text-slate-400" x-text="annual ? '/ year' : '/ month'">/ month</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-300 flex-1">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Up to 1,000 Students</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Everything in Basic</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Online Exams</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Fee Management</li>
                    </ul>
                    <a href="#" class="block w-full py-3 rounded-xl bg-blue-600 text-white text-center font-bold hover:bg-blue-500 transition-colors shadow-lg shadow-blue-600/25">Get Started</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="glass-card p-8 rounded-3xl relative overflow-hidden flex flex-col hover:border-purple-500/30 transition-all duration-300">
                    <h3 class="text-white font-bold text-2xl mb-2">Enterprise</h3>
                    <p class="text-slate-400 text-sm mb-6">Universities & Colleges</p>
                    <div class="mb-8">
                        <span class="text-3xl font-bold text-white">Contact Us</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-slate-300 flex-1">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Unlimited Students</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Everything in Standard</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Multi-Campus</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-400"></i> Dedicated Support</li>
                    </ul>
                    <a href="#" class="block w-full py-3 rounded-xl border border-slate-600 text-white text-center font-bold hover:bg-slate-800 transition-colors">Talk to Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Parallax Stats/CTA Section (Advanced) -->
    <section id="stats" class="relative py-48 overflow-hidden" data-aos="zoom-in" data-aos-duration="1000">
        <!-- Parallax BG -->
        <div class="absolute inset-0 z-0 bg-fixed"
            style="background-image: url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-slate-900/90 mix-blend-multiply z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Stats Grid with Counters -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center text-white mb-24"
                x-intersect="$el.querySelectorAll('.counter').forEach(el => {
                     let start = 0;
                     let end = parseInt(el.dataset.target);
                     let duration = 2000;
                     let stepTime = Math.abs(Math.floor(duration / end));
                     let timer = setInterval(() => {
                         start++;
                         el.innerText = start;
                         if (start >= end) clearInterval(timer);
                     }, stepTime);
                 })">

                <div class="group hover:transform hover:scale-110 transition-transform duration-300">
                    <div class="text-6xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300 filter drop-shadow-md">
                        <span class="counter" data-target="1000">0</span>+
                    </div>
                    <div class="text-sm font-bold uppercase tracking-widest opacity-80 group-hover:opacity-100 group-hover:text-blue-300 transition-colors">Students</div>
                </div>

                <div class="group hover:transform hover:scale-110 transition-transform duration-300">
                    <div class="text-6xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-300 filter drop-shadow-md">
                        <span class="counter" data-target="50">0</span>+
                    </div>
                    <div class="text-sm font-bold uppercase tracking-widest opacity-80 group-hover:opacity-100 group-hover:text-purple-300 transition-colors">Instructors</div>
                </div>

                <div class="group hover:transform hover:scale-110 transition-transform duration-300">
                    <div class="text-6xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300 filter drop-shadow-md">
                        <span class="counter" data-target="98">0</span>%
                    </div>
                    <div class="text-sm font-bold uppercase tracking-widest opacity-80 group-hover:opacity-100 group-hover:text-emerald-300 transition-colors">University Placement</div>
                </div>

                <div class="group hover:transform hover:scale-110 transition-transform duration-300">
                    <div class="text-6xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-300 filter drop-shadow-md">
                        <span class="counter" data-target="24">0</span>/7
                    </div>
                    <div class="text-sm font-bold uppercase tracking-widest opacity-80 group-hover:opacity-100 group-hover:text-orange-300 transition-colors">Support</div>
                </div>
            </div>

            <!-- CTA Card -->
            <div class="text-center max-w-5xl mx-auto bg-white/5 backdrop-blur-2xl p-16 rounded-[3rem] border border-white/10 shadow-2xl relative overflow-hidden group" data-aos="flip-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                <h2 class="text-5xl font-bold text-white mb-8 relative z-10">Ready to Transform Your Future?</h2>
                <p class="text-xl text-slate-300 mb-12 relative z-10 max-w-2xl mx-auto">Join thousands of students and educators who have already embraced the future of learning with Own Education.</p>

                <div class="flex flex-col sm:flex-row justify-center gap-6 relative z-10">
                    <a href="{{ route('student.register') }}" class="px-10 py-5 rounded-2xl bg-white text-slate-900 font-bold text-xl hover:bg-blue-50 transition-all hover:scale-105 shadow-xl hover:shadow-2xl hover:shadow-white/20">
                        Start Registration
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-900 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-2xl text-white">Own <span class="text-blue-400">Education</span></span>
                    </div>
                    <p class="text-slate-400 max-w-sm leading-relaxed text-lg">
                        Empowering educational institutions with cutting-edge technology for a brighter, more connected future.
                    </p>
                </div>

                <div>
                    <!-- Removed Quick Links -->
                </div>

                <div>
                    <h4 class="font-bold text-white text-lg mb-8">Contact</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4 text-slate-400 group">
                            <i class="fa-solid fa-location-dot mt-1 text-blue-400 group-hover:text-blue-300 transition-colors"></i>
                            <span class="group-hover:text-slate-300 transition-colors">123 Education Lane,<br>Academic City, AC 12345</span>
                        </li>
                        <li class="flex items-center gap-4 text-slate-400 group">
                            <i class="fa-solid fa-phone text-blue-400 group-hover:text-blue-300 transition-colors"></i>
                            <span class="group-hover:text-slate-300 transition-colors">+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center gap-4 text-slate-400 group">
                            <i class="fa-solid fa-envelope text-blue-400 group-hover:text-blue-300 transition-colors"></i>
                            <span class="group-hover:text-slate-300 transition-colors">support@owneducation.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-sm">
                    &copy; 2026 Own Education. All Rights Reserved.
                </p>
                <div class="flex gap-6">
                    <!-- Removed Social Links -->
                </div>
            </div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Initialization Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: false, // Allow animations to repeat on scroll up
            offset: 100,
            mirror: true // Animate elements while scrolling past them
        });

        // Initialize Alpine for intersection observer (counters)
        document.addEventListener('alpine:init', () => {
            Alpine.directive('intersect', (el, {
                expression
            }, {
                evaluateLater,
                cleanup
            }) => {
                let observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            evaluateLater(expression)();
                            observer.disconnect(); // Run once
                        }
                    })
                })
                observer.observe(el);
                cleanup(() => observer.disconnect());
            });
        });

        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            initialSlide: 3,
            coverflowEffect: {
                rotate: 20,
                stretch: 0,
                depth: 350,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            // Infinite Linear Loop Configuration
            speed: 3000,
            loop: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                pauseOnMouseEnter: false
            },
            allowTouchMove: true,
            freeMode: true,
            freeModeMomentum: false,
        });

        // Initialize Testimonial Swiper
        var testimonialSwiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });

        // Force linear animation for continuous effect
        const swiperWrapper = document.querySelector('.swiper-wrapper');
        swiperWrapper.style.transitionTimingFunction = 'linear';
    </script>

    <!-- Live Chat Widget -->
    <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50">
        <!-- Chat Button -->
        <button @click="open = !open" class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg hover:bg-blue-500 transition-all hover:scale-110 active:scale-95 group">
            <i class="fa-brands fa-whatsapp text-3xl" x-show="!open"></i>
            <i class="fa-solid fa-xmark text-xl" x-show="open" x-cloak></i>
            <!-- Notification Dot -->
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-900"></span>
        </button>

        <!-- Chat Popup -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            class="absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden mb-2" x-cloak>

            <div class="bg-blue-600 p-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="font-bold">Admissions Support</h4>
                    <p class="text-xs text-blue-100">Typically replies in 5 mins</p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 h-64 overflow-y-auto space-y-3 text-sm">
                <div class="bg-white p-3 rounded-lg rounded-tl-none shadow-sm max-w-[85%] text-slate-700">
                    Hello! 👋 Welcome to Own Education. How can we help you today?
                </div>
                <!-- Mock User Message -->
                <!-- <div class="bg-blue-100 p-3 rounded-lg rounded-tr-none shadow-sm max-w-[85%] ml-auto text-blue-900">
                    Hi, I have a question about fees.
                </div> -->
            </div>
            <div class="p-3 bg-white border-t border-slate-100">
                <a href="https://wa.me/03409172223" target="_blank" class="block w-full text-center bg-green-500 text-white font-bold py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fa-brands fa-whatsapp mr-2"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</body>


</html>