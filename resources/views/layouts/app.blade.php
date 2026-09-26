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
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ request()->routeIs('admin.*') ? 'bg-primary-container text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                            <span class="material-symbols-outlined text-[16px] mr-1">admin_panel_settings</span> Panel Admin 3T
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- Right Action Items -->
            <div class="flex items-center gap-3">

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
                    <!-- Authenticated User Menu Toggle & Modal -->
                    <div class="relative" id="userMenuWrapper">
                        <button type="button" 
                                id="profileMenuToggle" 
                                onclick="toggleProfileModal(event)"
                                class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-surface-container-low transition-all border border-transparent hover:border-surface-container-high focus:outline-none focus:ring-2 focus:ring-secondary/40 cursor-pointer">
                            <div class="text-right hidden sm:block pl-1">
                                <span class="block text-xs font-bold text-on-surface leading-tight">{{ auth()->user()->name }}</span>
                                <span class="block text-[11px] text-on-surface-variant capitalize">{{ auth()->user()->isTeacher() ? 'Guru Relawan' : (auth()->user()->isStudent() ? 'Murid' : 'Admin') }}</span>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-xs shadow-sm ring-2 ring-surface-container-high shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant hidden sm:inline transition-transform duration-200" id="profileChevron">expand_more</span>
                        </button>

                        <!-- Profile Modal Dropdown -->
                        <div id="profileModal" 
                             class="hidden absolute right-0 top-full mt-2 w-72 sm:w-80 bg-white rounded-3xl shadow-xl border border-surface-container-high p-4 z-50 animate-in fade-in zoom-in-95 duration-150">
                            
                            <!-- 1. Username & Role -->
                            <div class="p-3 bg-surface-container-low rounded-2xl flex items-center gap-3 mb-3 border border-surface-container-high/60">
                                <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center font-extrabold text-base shadow-sm shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-sm font-extrabold text-primary truncate leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="block text-[11px] text-on-surface-variant truncate mt-0.5">{{ auth()->user()->email }}</span>
                                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ auth()->user()->isTeacher() ? 'bg-secondary-container text-on-secondary-container' : (auth()->user()->isStudent() ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-primary-container text-on-primary') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ auth()->user()->isTeacher() ? 'bg-secondary' : 'bg-primary' }}"></span>
                                        {{ auth()->user()->isTeacher() ? 'Guru Relawan' : (auth()->user()->isStudent() ? 'Murid / Siswa' : 'Administrator') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="space-y-1.5 border-t border-surface-container-high pt-2">
                                
                                <!-- 2. Tombol Navigasi ke Halaman Edit Profil -->
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-primary hover:bg-surface-container-low transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high/60 flex items-center justify-center group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span>Edit Profil Pribadi</span>
                                        <span class="text-[10px] text-on-surface-variant font-normal">Perbarui data diri &amp; akun</span>
                                    </div>
                                </a>

                                <!-- 3. Tombol Navigasi ke Halaman Histori Pembelajaran -->
                                <a href="{{ route('history.index') }}" 
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-primary hover:bg-surface-container-low transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high/60 flex items-center justify-center group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">history_edu</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span>Histori Pembelajaran</span>
                                        <span class="text-[10px] text-on-surface-variant font-normal">Riwayat sesi belajar &amp; catatan</span>
                                    </div>
                                </a>

                                <!-- 4. Tombol Logout -->
                                <div class="pt-1 border-t border-surface-container-high">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-error hover:bg-error/10 transition-colors group">
                                            <div class="w-8 h-8 rounded-lg bg-error/10 flex items-center justify-center text-error group-hover:bg-error group-hover:text-white transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                            </div>
                                            <div class="flex flex-col text-left">
                                                <span>Keluar Akun</span>
                                                <span class="text-[10px] text-error/80 font-normal">Akhiri sesi di perangkat ini</span>
                                            </div>
                                        </button>
                                    </form>
                                </div>

                            </div>

                        </div>
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
                <div class="p-3 bg-surface-container-low rounded-xl mb-2 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-primary truncate">{{ auth()->user()->name }}</span>
                        <span class="block text-[10px] text-on-surface-variant capitalize">{{ auth()->user()->isTeacher() ? 'Guru Relawan' : (auth()->user()->isStudent() ? 'Murid / Siswa' : 'Administrator') }}</span>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                    <span>Edit Profil Pribadi</span>
                </a>

                <a href="{{ route('history.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                    <span>Histori Pembelajaran</span>
                </a>

                @if(auth()->user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                        Ruang Belajarku (Dashboard)
                    </a>
                @elseif(auth()->user()->isTeacher())
                    <a href="{{ route('teacher.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                        Kelola Jadwal & Booking (Dashboard)
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface-container-low">
                        Panel Kendali Admin 3T
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-surface-container-high">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-error hover:bg-error-container/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Keluar Akun (Logout)</span>
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

        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="p-4 bg-error-container/60 border border-error text-error rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 font-bold text-sm">
                            <span class="material-symbols-outlined text-error text-[20px]">warning</span>
                            <span>Terdapat kendala pada isian formulir:</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-error hover:opacity-75">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-6 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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

    <!-- Mobile Drawer & Profile Modal Script Toggle -->
    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        function toggleProfileModal(event) {
            if (event) event.stopPropagation();
            const modal = document.getElementById('profileModal');
            const chevron = document.getElementById('profileChevron');
            if (modal) {
                modal.classList.toggle('hidden');
                if (chevron) {
                    chevron.classList.toggle('rotate-180');
                }
            }
        }

        // Tutup modal profil saat klik di luar area
        document.addEventListener('click', (event) => {
            const modal = document.getElementById('profileModal');
            const toggle = document.getElementById('profileMenuToggle');
            const chevron = document.getElementById('profileChevron');
            if (modal && !modal.classList.contains('hidden')) {
                if (!modal.contains(event.target) && !toggle.contains(event.target)) {
                    modal.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        });

        // Tutup modal profil saat tombol Escape ditekan
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                const modal = document.getElementById('profileModal');
                const chevron = document.getElementById('profileChevron');
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        });

        // ==========================================
        // REAL-TIME SCHEDULE COUNTDOWN ENGINE
        // ==========================================
        window.SERVER_TIME_MS = {{ now()->getTimestampMs() }};
        window.CLIENT_LOAD_TIME_MS = Date.now();

        function getNowWIB() {
            return new Date(window.SERVER_TIME_MS + (Date.now() - window.CLIENT_LOAD_TIME_MS));
        }

        function formatTimeDurationHuman(ms) {
            if (ms <= 0) return '0 dtk';
            const totalSeconds = Math.floor(ms / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (days > 0) {
                return `${days} hr ${hours} jam ${minutes} mnt`;
            }
            if (hours > 0) {
                return `${hours} jam ${minutes} mnt ${seconds} dtk`;
            }
            if (minutes > 0) {
                return `${minutes} mnt ${seconds} dtk`;
            }
            return `${seconds} dtk`;
        }

        function tickRealtimeSchedules() {
            const now = getNowWIB();
            const targets = document.querySelectorAll('.realtime-schedule-pill, .realtime-schedule-badge');

            targets.forEach(el => {
                const startStr = el.getAttribute('data-start-time');
                const endStr = el.getAttribute('data-end-time');
                const mode = el.getAttribute('data-mode') || 'default';
                const bookingId = el.getAttribute('data-booking-id');

                if (!startStr) return;

                const startTime = new Date(startStr);
                const endTime = endStr ? new Date(endStr) : null;
                const textTarget = el.querySelector('.realtime-text') || el;

                const msToStart = startTime.getTime() - now.getTime();
                const msToEnd = endTime ? (endTime.getTime() - now.getTime()) : -1;

                if (msToStart > 0) {
                    // Sesi Belum Dimulai
                    const countdownStr = formatTimeDurationHuman(msToStart);
                    if (mode === 'hero') {
                        el.className = 'realtime-schedule-pill inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-900 border border-blue-200 transition-all shadow-xs';
                        textTarget.innerHTML = `<span class="material-symbols-outlined text-[15px] text-blue-600 animate-pulse">schedule</span> Dimulai dlm: <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else if (mode === 'teacher') {
                        textTarget.innerHTML = `Dimulai dalam ${countdownStr}`;
                    } else if (mode === 'pending') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center gap-1';
                        textTarget.innerHTML = `<span class="material-symbols-outlined text-[13px] text-blue-600">timer</span> Mulai dlm <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else if (mode === 'slot') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1';
                        textTarget.innerHTML = `<span class="material-symbols-outlined text-[13px] text-blue-600">timer</span> Mulai dlm <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else if (mode === 'compact') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center gap-1';
                        textTarget.innerHTML = `<span class="material-symbols-outlined text-[13px] text-blue-600">schedule</span> Mulai dlm <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else {
                        textTarget.innerHTML = `Dimulai dlm ${countdownStr}`;
                    }
                } else if (endTime && msToEnd > 0) {
                    // Sesi Sedang Berlangsung
                    const countdownStr = formatTimeDurationHuman(msToEnd);
                    if (mode === 'hero') {
                        el.className = 'realtime-schedule-pill inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300 transition-all shadow-xs';
                        textTarget.innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-600 animate-ping"></span> Berlangsung • Selesai dlm: <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else if (mode === 'teacher') {
                        textTarget.innerHTML = `Selesai dalam ${countdownStr}`;
                    } else if (mode === 'slot') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1';
                        textTarget.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Berlangsung • Selesai dlm <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else if (mode === 'compact' || mode === 'pending') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1';
                        textTarget.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Selesai dlm <span class="tabular-nums font-mono">${countdownStr}</span>`;
                    } else {
                        textTarget.innerHTML = `Berlangsung • Selesai dlm ${countdownStr}`;
                    }
                } else {
                    // Sesi Telah Selesai
                    if (mode === 'hero') {
                        el.className = 'realtime-schedule-pill inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300 transition-all';
                        textTarget.innerHTML = `<span class="material-symbols-outlined text-[15px] text-emerald-600">task_alt</span> Waktu Sesi Selesai`;
                    } else if (mode === 'teacher') {
                        textTarget.innerHTML = `Waktu Sesi Berakhir`;
                    } else if (mode === 'slot') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2 py-0.5 rounded-md bg-surface-container-high text-outline inline-flex items-center gap-1';
                        textTarget.innerHTML = `Jadwal telah lewat`;
                    } else if (mode === 'compact' || mode === 'pending') {
                        el.className = 'realtime-schedule-pill text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-surface-container-high text-outline inline-flex items-center gap-1';
                        textTarget.innerHTML = `Waktu Selesai`;
                    } else {
                        textTarget.innerHTML = `Waktu Selesai`;
                    }

                    // Realtime unlock form guru tanpa refresh
                    if (bookingId) {
                        const lockedBox = document.getElementById(`session-locked-${bookingId}`);
                        const unlockedBox = document.getElementById(`session-unlocked-${bookingId}`);
                        if (lockedBox && unlockedBox && !lockedBox.classList.contains('hidden')) {
                            lockedBox.classList.add('hidden');
                            unlockedBox.classList.remove('hidden');
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            tickRealtimeSchedules();
            setInterval(tickRealtimeSchedules, 1000);
        });
    </script>

    @stack('scripts')
</body>
</html>
