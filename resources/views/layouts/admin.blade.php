<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel — Ajari Aku Nusantara')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
<body class="bg-surface-container-low font-sans text-on-surface antialiased min-h-screen flex flex-col">

    <!-- Top Command & Status Bar (Pusat Kendali Operasional 3T) -->
    <header class="bg-primary text-white border-b border-primary-container sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-secondary-container rounded-xl flex items-center justify-center text-on-secondary-container shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px] font-bold">shield_person</span>
                    </div>
                    <div>
                        <span class="font-extrabold text-base tracking-tight block leading-tight">Admin Kendali 3T</span>
                        <span class="text-[10px] text-surface-dim uppercase font-semibold">Ajari Aku Nusantara</span>
                    </div>
                </a>
            </div>

            <!-- Central Status Indicator -->
            <div class="hidden md:flex items-center gap-3 text-xs bg-white/10 px-4 py-1.5 rounded-full border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary-container animate-pulse"></span>
                <span class="font-bold text-secondary-container">Sistem Operasional Normal</span>
                <span class="text-surface-dim">• Sinkronisasi Realtime</span>
            </div>

            <!-- User Menu & Back to Main Web -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-surface-dim hover:text-white px-3 py-1.5 rounded-lg hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>Lihat Web Siswa</span>
                </a>

                <div class="flex items-center gap-2 border-l border-white/20 pl-3">
                    <span class="text-xs font-bold hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar Akun" class="p-1.5 text-surface-dim hover:text-error rounded-lg hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Sub-header (Tabs / Breadcrumb Navigation) -->
    <nav class="bg-white border-b border-surface-container-high px-4 sm:px-6 lg:px-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center gap-2 overflow-x-auto py-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                <span>Dashboard & Dampak</span>
            </a>

            @php
                $pendingTeachersCount = \App\Models\Teacher::where('verification_status', 'pending')->count();
            @endphp
            <a href="{{ route('admin.teachers.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all {{ request()->routeIs('admin.teachers.*') ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Verifikasi Guru & Relawan</span>
                @if($pendingTeachersCount > 0)
                    <span class="bg-amber-500 text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full">
                        {{ $pendingTeachersCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">group</span>
                <span>Manajemen Pengguna</span>
            </a>

            <a href="{{ route('admin.sessions.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all {{ request()->routeIs('admin.sessions.*') ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">sensors</span>
                <span>Monitoring Sesi 3T</span>
            </a>
        </div>
    </nav>

    <!-- Main Content Workspace -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1">
        <!-- Flash Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-secondary-container/60 border border-secondary text-on-secondary-container rounded-2xl flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">check_circle</span>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-secondary hover:opacity-75">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-error-container/60 border border-error text-error rounded-2xl flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-error text-[22px]">error</span>
                    <span class="text-xs font-bold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-error hover:opacity-75">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 p-4 bg-surface-container-high border border-outline-variant text-on-surface rounded-2xl flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[22px]">info</span>
                    <span class="text-xs font-bold">{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-outline hover:opacity-75">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Admin -->
    <footer class="bg-white border-t border-surface-container-high py-4 text-center text-xs text-outline">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} Ajari Aku — Platform Edukasi Penghubung Siswa Daerah Terpencil & Guru Relawan.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
