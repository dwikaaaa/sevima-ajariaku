<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ajari Aku — Mentoring Pelosok Nusantara')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Tailwind CSS with CDN & Configuration matching UI/UX design tokens -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#002821",
                        "primary-container": "#123f36",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#7eaa9e",
                        "background": "#e9fef8",
                        "surface": "#e9fef8",
                        "surface-bright": "#e9fef8",
                        "surface-dim": "#caded9",
                        "surface-variant": "#d2e7e2",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#e3f8f3",
                        "surface-container": "#def2ed",
                        "surface-container-high": "#d8ede7",
                        "surface-container-highest": "#d2e7e2",
                        "on-surface": "#0d1f1c",
                        "on-surface-variant": "#404846",
                        "secondary": "#006d3d",
                        "secondary-container": "#97f3b5",
                        "on-secondary-container": "#047240",
                        "tertiary": "#331e00",
                        "tertiary-fixed": "#ffddb6",
                        "tertiary-fixed-dim": "#ffb959",
                        "outline": "#717976",
                        "outline-variant": "#c0c8c4",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6"
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .material-symbols-fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface-container-lowest font-sans text-on-surface antialiased flex flex-col min-h-screen">

    <!-- Top Navigation Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand Logo & Title -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 32C17 29 21 29 24 31V16C21 14 17 14 12 16V32Z" fill="#E8F5E9"></path>
                            <path d="M36 32C31 29 27 29 24 31V16C27 14 31 14 36 16V32Z" fill="#A3E4D7"></path>
                            <circle cx="24" cy="11" fill="#F4D03F" r="3.2"></circle>
                            <path d="M24 18V29" stroke="#ffffff" stroke-linecap="round" stroke-width="2.5"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl text-primary leading-tight tracking-tight">Ajari Aku</span>
                        <span class="text-[11px] font-semibold text-on-surface-variant">Mentoring Pelosok Nusantara</span>
                    </div>
                </a>

                <!-- Role Status Indicator (Jalur Sisi Pengguna) -->
                @auth
                    <div class="hidden xl:flex items-center bg-surface-container-low px-3 py-1 rounded-full text-xs font-semibold text-primary">
                        <span class="w-2 h-2 rounded-full mr-2 {{ auth()->user()->isTeacher() ? 'bg-secondary' : 'bg-primary-container' }}"></span>
                        Peran: {{ auth()->user()->isTeacher() ? 'Guru Relawan' : (auth()->user()->isStudent() ? 'Murid / Siswa' : 'Administrator') }}
                    </div>
                @endauth
            </div>

            <!-- Central Nav Links -->
            <nav class="hidden md:flex items-center gap-1 bg-surface-container-low/70 p-1.5 rounded-full border border-surface-container-high">
                <a href="{{ route('teachers.index') }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ request()->routeIs('teachers.*') || request()->routeIs('home') ? 'bg-primary-container text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                    <span class="material-symbols-outlined text-[16px] mr-1">search</span> Cari Guru
                </a>

                @auth
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" 
                           class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ request()->routeIs('student.*') ? 'bg-primary-container text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                            <span class="material-symbols-outlined text-[16px] mr-1">school</span> Ruang Belajarku
                        </a>
                    @elseif(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.dashboard') }}" 
                           class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ request()->routeIs('teacher.*') ? 'bg-primary-container text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                            <span class="material-symbols-outlined text-[16px] mr-1">calendar_month</span> Kelola Jadwal & Murid
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- Right Action Items -->
            <div class="flex items-center gap-3">
                <!-- Mode Hemat Kuota Indicator -->
                <div class="hidden sm:flex items-center gap-1.5 bg-secondary-container/40 text-on-secondary-container px-3 py-1.5 rounded-full text-xs font-bold border border-secondary-container">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="material-symbols-outlined text-[15px]">network_check</span>
                    <span>Mode Hemat Kuota</span>
                </div>

                @guest
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-full text-xs font-bold text-primary hover:bg-surface-container-low transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-primary text-on-primary hover:bg-primary-container shadow-sm transition-all">
                            Daftar Sekarang
                        </a>
                    </div>
                @else
                    <!-- Authenticated User Menu -->
                    <div class="relative flex items-center gap-3" id="userMenuWrapper">
                        <div class="text-right hidden sm:block">
                            <span class="block text-xs font-bold text-on-surface leading-tight">{{ auth()->user()->name }}</span>
                            <span class="block text-[11px] text-on-surface-variant capitalize">{{ auth()->user()->role }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-sm shadow-sm ring-2 ring-surface-container-high">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" title="Keluar Akun" class="p-2 text-outline hover:text-error rounded-full hover:bg-surface-container-low transition-colors">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                            </button>
                        </form>
                    </div>
                @endguest

                <!-- Mobile Menu Button -->
                <button type="button" id="mobileMenuBtn" class="md:hidden p-2 rounded-lg text-on-surface hover:bg-surface-container-low">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-surface-container-high bg-white px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('teachers.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                Cari Guru Relawan
            </a>
            @auth
                @if(auth()->user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                        Ruang Belajarku (Dashboard)
                    </a>
                @elseif(auth()->user()->isTeacher())
                    <a href="{{ route('teacher.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                        Kelola Jadwal & Booking (Dashboard)
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-surface-container-high">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-error hover:bg-error-container/30">
                        Keluar Akun (Logout)
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-surface-container-high">
                    <a href="{{ route('login') }}" class="text-center py-2 rounded-lg text-xs font-bold text-primary bg-surface-container-low">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center py-2 rounded-lg text-xs font-bold text-white bg-primary">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="w-full pt-20 flex-1 bg-surface-container-lowest">
        <!-- Flash Feedback Notifications -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 bg-secondary-container/60 border border-secondary text-on-secondary-container rounded-xl flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">check_circle</span>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-secondary hover:opacity-75">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 bg-error-container/60 border border-error text-error rounded-xl flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-error text-[22px]">error</span>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-error hover:opacity-75">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 bg-surface-container-high border border-outline-variant text-on-surface rounded-xl flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">info</span>
                        <span class="text-sm font-semibold">{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-outline hover:opacity-75">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Page Yield Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-primary text-white border-t border-primary-container mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary-container text-[20px]">volunteer_activism</span>
                        </div>
                        <span class="font-bold text-lg tracking-tight">Ajari Aku</span>
                    </div>
                    <p class="text-xs text-surface-dim leading-relaxed max-w-md">
                        Gerakan revolusi edukasi yang menghubungkan adik-adik pelajar di wilayah 3T (Tertinggal, Terdepan, dan Terluar) dengan para guru dan mentor relawan berdedikasi dari seluruh penjuru Indonesia.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-secondary-container font-semibold pt-1">
                        <span class="material-symbols-outlined text-[16px]">favorite</span>
                        <span>100% Bebas Biaya • Didukung Koneksi Ramah Data</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-surface-dim mb-3">Tautan Cepat</h3>
                    <ul class="space-y-2 text-xs text-surface-dim">
                        <li><a href="{{ route('teachers.index') }}" class="hover:text-white transition-colors">Cari Guru Relawan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Gabung Sebagai Relawan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Sebagai Siswa</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-surface-dim mb-3">Bantuan & Sinyal</h3>
                    <ul class="space-y-2 text-xs text-surface-dim">
                        <li class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-secondary-container">wifi_tethering</span>
                            <span>Panduan Hemat Kuota</span>
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-secondary-container">support_agent</span>
                            <span>Pusat Bantuan WhatsApp</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-surface-dim gap-2">
                <span>&copy; {{ date('Y') }} Ajari Aku. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibangun untuk Pemerataan Pendidikan Nusantara.</span>
            </div>
        </div>
    </footer>

    <!-- Mobile Drawer Script Toggle -->
    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
