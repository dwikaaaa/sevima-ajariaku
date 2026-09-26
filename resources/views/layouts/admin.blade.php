<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Portal — Ajari Aku Nusantara')</title>

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

        /* Notifikasi Animasi: Slide In dari Kanan ke Kiri & Slide Out dari Kiri ke Kanan */
        @keyframes toastSlideInRightToLeft {
            0% {
                transform: translateX(120%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOutLeftToRight {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(120%);
                opacity: 0;
            }
        }

        @keyframes toastProgress {
            0% { width: 100%; }
            100% { width: 0%; }
        }

        .toast-slide-in {
            animation: toastSlideInRightToLeft 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .toast-slide-out {
            animation: toastSlideOutLeftToRight 0.4s cubic-bezier(0.7, 0, 0.84, 0) forwards;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-on-surface bg-[#f4f7f6] flex flex-col">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm hidden lg:hidden" onclick="toggleAdminSidebar()"></div>

    <!-- Left Sidebar (Panel Navigasi Admin) -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#002821] text-white flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 border-r border-[#123f36] shadow-xl">
        
        <!-- Top Section: Brand Logo & Title -->
        <div class="flex flex-col">
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-[#001f19]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-secondary-container text-on-secondary-container rounded-xl flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-[22px] font-bold">shield_person</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-base tracking-tight text-white leading-tight">Ajari Aku</span>
                        </div>
                        <span class="inline-block text-[10px] font-bold text-secondary-container uppercase tracking-wider">
                            Admin Portal
                        </span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button type="button" class="lg:hidden text-gray-300 hover:text-white p-1" onclick="toggleAdminSidebar()">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-6 space-y-1.5 overflow-y-auto max-h-[calc(100vh-160px)]">
                
                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container text-on-secondary-container shadow-sm font-extrabold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin.dashboard') ? 'material-symbols-fill' : '' }}">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <!-- 2. User Management (Dropdown) -->
                @php
                    $isUserRoute = request()->routeIs('admin.users.*');
                    $activeRole = request('role', 'all');
                @endphp
                <div>
                    <button type="button" 
                            id="userDropdownBtn"
                            onclick="toggleUserDropdown()"
                            class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $isUserRoute ? 'bg-white/15 text-white font-extrabold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] {{ $isUserRoute ? 'material-symbols-fill text-secondary-container' : '' }}">group</span>
                            <span>Manajemen User</span>
                        </div>
                        <span id="userDropdownChevron" class="material-symbols-outlined text-[18px] transition-transform duration-200 {{ $isUserRoute ? 'rotate-180' : '' }}">
                            expand_more
                        </span>
                    </button>

                    <!-- Sub-menu Dropdown -->
                    <div id="userDropdownMenu" class="pl-4 pr-1 pt-1.5 pb-1 space-y-1 {{ $isUserRoute ? '' : 'hidden' }}">
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $isUserRoute && $activeRole === 'all' ? 'bg-secondary-container/20 text-secondary-container font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isUserRoute && $activeRole === 'all' ? 'bg-secondary-container' : 'bg-gray-400' }}"></span>
                                <span>Semua Pengguna</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $isUserRoute && $activeRole === 'guru' ? 'bg-secondary-container/20 text-secondary-container font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isUserRoute && $activeRole === 'guru' ? 'bg-secondary-container' : 'bg-gray-400' }}"></span>
                                <span>Guru Relawan</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $isUserRoute && $activeRole === 'siswa' ? 'bg-secondary-container/20 text-secondary-container font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isUserRoute && $activeRole === 'siswa' ? 'bg-secondary-container' : 'bg-gray-400' }}"></span>
                                <span>Murid / Siswa 3T</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 3. Verifikasi Guru & Relawan -->
                @php
                    $pendingCount = \App\Models\Teacher::where('verification_status', 'pending')->count();
                @endphp
                <a href="{{ route('admin.teachers.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.teachers.*') ? 'bg-secondary-container text-on-secondary-container shadow-sm font-extrabold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin.teachers.*') ? 'material-symbols-fill' : '' }}">verified</span>
                        <span>Verifikasi Guru</span>
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-amber-400 text-gray-900 text-[10px] font-black px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- 4. Monitoring Sesi Belajar -->
                <a href="{{ route('admin.sessions.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.sessions.*') ? 'bg-secondary-container text-on-secondary-container shadow-sm font-extrabold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('admin.sessions.*') ? 'material-symbols-fill' : '' }}">sensors</span>
                    <span>Monitoring Sesi</span>
                </a>

            </nav>
        </div>

        <!-- Bottom User Card & Quick Logout -->
        <div class="p-4 border-t border-white/10 bg-[#001f19]">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
                        <span class="block text-[10px] text-gray-400 truncate">Administrator</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar Akun" class="p-2 rounded-lg text-gray-400 hover:text-error hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- Main Content Workspace (Offset by Left Sidebar on Desktop) -->
    <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
        
        <!-- Clean Admin Topbar (No "Cari Guru" link) -->
        <header class="h-20 bg-white border-b border-gray-200 sticky top-0 z-30 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
            
            <!-- Left: Mobile Toggle & Page Title / Breadcrumb -->
            <div class="flex items-center gap-3">
                <button type="button" 
                        class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 focus:outline-none" 
                        onclick="toggleAdminSidebar()">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold text-primary uppercase tracking-wider hidden sm:inline">Pusat Kendali</span>
                    <span class="text-gray-300 hidden sm:inline">•</span>
                    <span class="text-xs font-bold text-gray-600">@yield('page_title', 'Ajari Aku Mentoring Nusantara')</span>
                </div>
            </div>

            <!-- Right: System Status & User Quick Info -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 px-3 py-1.5 rounded-full text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistem Terhubung</span>
                </div>

                <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                    <span class="text-xs font-bold text-gray-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-gray-400 hover:text-error rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">power_settings_new</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            
        <!-- Floating Fixed Toast Notifications (Tidak menggeser layout) -->
        <div id="adminToastNotificationContainer" class="fixed top-20 right-4 sm:right-8 z-[9999] flex flex-col items-end gap-3 max-w-md w-[calc(100%-2rem)] sm:w-auto pointer-events-none">
            @if (session('success'))
                <div class="app-toast-item pointer-events-auto toast-slide-in p-4 bg-white/95 backdrop-blur-md border border-emerald-300 text-emerald-950 rounded-2xl shadow-xl flex flex-col gap-1.5 min-w-[300px] sm:min-w-[360px]" role="alert">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-emerald-600 text-[24px] shrink-0 mt-0.5">check_circle</span>
                            <div>
                                <span class="block text-xs font-bold text-emerald-900">Berhasil!</span>
                                <span class="text-xs text-emerald-800 leading-relaxed font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="toast-close-btn text-emerald-700 hover:text-emerald-900 hover:bg-emerald-100/60 p-1 rounded-lg transition-colors shrink-0" aria-label="Tutup">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <div class="h-1 bg-emerald-100 rounded-full overflow-hidden mt-1">
                        <div class="h-full bg-emerald-500 toast-progress-bar" style="animation: toastProgress 5s linear forwards;"></div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="app-toast-item pointer-events-auto toast-slide-in p-4 bg-white/95 backdrop-blur-md border border-red-300 text-red-950 rounded-2xl shadow-xl flex flex-col gap-1.5 min-w-[300px] sm:min-w-[360px]" role="alert">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-red-600 text-[24px] shrink-0 mt-0.5">error</span>
                            <div>
                                <span class="block text-xs font-bold text-red-900">Pemberitahuan</span>
                                <span class="text-xs text-red-800 leading-relaxed font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button type="button" class="toast-close-btn text-red-700 hover:text-red-900 hover:bg-red-100/60 p-1 rounded-lg transition-colors shrink-0" aria-label="Tutup">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <div class="h-1 bg-red-100 rounded-full overflow-hidden mt-1">
                        <div class="h-full bg-red-500 toast-progress-bar" style="animation: toastProgress 5s linear forwards;"></div>
                    </div>
                </div>
            @endif
        </div>

            @yield('content')
        </main>

        <!-- Footer Admin -->
        <footer class="bg-white border-t border-gray-200 px-6 py-4 text-center sm:flex sm:items-center sm:justify-between text-xs text-gray-500">
            <span>&copy; {{ date('Y') }} Ajari Aku — Mentoring Pelosok Nusantara. Seluruh Hak Cipta Dilindungi.</span>
            <span class="mt-2 sm:mt-0 font-medium">Panel Kendali Administrator v2.0</span>
        </footer>

    </div>

    <!-- Sidebar & Dropdown Scripts -->
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleUserDropdown() {
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('userDropdownChevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function dismissToastItem(toastEl) {
            if (!toastEl || toastEl.dataset.dismissing === 'true') return;
            toastEl.dataset.dismissing = 'true';
            toastEl.classList.remove('toast-slide-in');
            toastEl.classList.add('toast-slide-out');
            setTimeout(() => {
                toastEl.remove();
            }, 420);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.app-toast-item');
            toasts.forEach(toast => {
                const autoDismissTimer = setTimeout(() => {
                    dismissToastItem(toast);
                }, 5000);

                const closeBtn = toast.querySelector('.toast-close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        clearTimeout(autoDismissTimer);
                        dismissToastItem(toast);
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
