@extends('layouts.app')

@section('title', 'Cari Guru Relawan — Ajari Aku')

@section('content')
<div class="flex flex-col w-full">

    <!-- Status & Mode Hemat Data Strip -->
    <div class="w-full bg-surface-container-low py-2.5 px-4 sm:px-8 border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 text-on-surface-variant text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
                <span class="font-bold text-primary">Jaringan Terdeteksi Stabil (Hemat Data Aktif)</span>
                <span class="hidden sm:inline text-outline">• Akses optimal tanpa beban kuota video berat</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1 text-primary">
                    <span class="material-symbols-outlined text-[15px]">schedule</span> {{ now()->format('H:i') }} WIB
                </span>
                <span class="flex items-center gap-1 text-secondary font-bold">
                    <span class="material-symbols-outlined text-[15px]">verified</span> {{ $teachers->total() }} Guru Siap Mengajar
                </span>
            </div>
        </div>
    </div>

    <!-- Hero & Search Section -->
    <section class="w-full bg-white px-4 sm:px-8 pt-8 pb-10 border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto flex flex-col gap-6">

            <!-- Greeting Banner -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-surface-container-low rounded-2xl p-6 sm:p-8 border border-surface-container-high">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center shrink-0 shadow-sm">
                        <span class="material-symbols-outlined text-[32px]">waving_hand</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="inline-flex items-center gap-1 text-secondary text-xs font-bold uppercase tracking-wider mb-1">
                            <span class="material-symbols-outlined text-[16px]">stars</span>
                            @auth
                                {{ auth()->user()->isStudent() ? 'Siswa Bersemangat Pelosok' : (auth()->user()->isTeacher() ? 'Guru Relawan Pendidik' : 'Pengguna Komunitas') }}
                            @else
                                Sahabat Pendidikan Nusantara
                            @endauth
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-primary leading-tight">
                            @auth
                                Halo, {{ auth()->user()->name }}! Mau belajar apa hari ini?
                            @else
                                Temukan Guru Relawan Berdedikasi untuk Masa Depanmu
                            @endauth
                        </h1>
                        <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                            Pilih guru relawan terbaik dari seluruh Indonesia. Belajar interaktif, ramah kuota, dan sepenuhnya gratis.
                        </p>
                    </div>
                </div>

                <!-- Quick Stats Badge -->
                <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl shrink-0 shadow-sm border border-surface-container-high">
                    <div class="flex flex-col text-left">
                        <span class="text-[11px] text-on-surface-variant font-medium">Relawan Aktif</span>
                        <span class="text-lg font-extrabold text-primary">{{ $teachers->total() }} Guru Terdaftar</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-secondary-container/50 text-on-secondary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">military_tech</span>
                    </div>
                </div>
            </div>

            <!-- Search Form Filter -->
            <form action="{{ route('teachers.index') }}" method="GET" class="space-y-4">
                <div class="w-full flex flex-col md:flex-row items-stretch gap-2 bg-white p-2 rounded-2xl shadow-sm border border-surface-container-high">
                    <div class="flex-1 flex items-center px-4 py-2 gap-3">
                        <span class="material-symbols-outlined text-outline text-[24px]">search</span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search', $search ?? '') }}" 
                               placeholder="Ketik pelajaran, asal kota, atau nama guru (contoh: Matematika SD, Kak Sarah)..." 
                               class="w-full bg-transparent text-sm text-on-surface placeholder:text-outline border-none focus:ring-0 focus:outline-none">
                        @if(request('search'))
                            <a href="{{ route('teachers.index') }}" class="text-outline hover:text-on-surface p-1" title="Hapus pencarian">
                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="bg-primary text-on-primary text-xs font-bold px-6 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-primary-container transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">manage_search</span>
                        <span>Cari Guru Sekarang</span>
                    </button>
                </div>

                <!-- Filter Pills: Mata Pelajaran & Lokasi -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <span class="text-[11px] text-on-surface-variant uppercase tracking-wider font-bold">Pilih Kategori Mata Pelajaran</span>
                        @if(request('subject') || request('origin_location') || request('search'))
                            <a href="{{ route('teachers.index') }}" class="text-xs font-semibold text-error hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">refresh</span> Reset Semua Filter
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('teachers.index', array_merge(request()->except(['subject', 'page']))) }}" 
                           class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ !request('subject') ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest' }}">
                            Semua Pelajaran
                        </a>

                        @foreach($availableSubjects->take(6) as $subj)
                            <a href="{{ route('teachers.index', array_merge(request()->all(), ['subject' => $subj, 'page' => 1])) }}" 
                               class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ request('subject') === $subj ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest' }}">
                                {{ $subj }}
                            </a>
                        @endforeach

                        <!-- Location Dropdown Filter -->
                        <div class="relative inline-block ml-auto">
                            <select name="origin_location" 
                                    onchange="this.form.submit()" 
                                    class="px-4 py-2 rounded-full text-xs font-bold bg-surface-container-low text-primary border-none focus:ring-2 focus:ring-primary-container cursor-pointer pr-8">
                                <option value="">🌍 Asal Guru: Semua Kota</option>
                                @foreach($availableLocations as $loc)
                                    <option value="{{ $loc }}" {{ request('origin_location') === $loc ? 'selected' : '' }}>
                                        📍 {{ $loc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>

    <!-- Main Grid Section: Guru Relawan -->
    <section class="max-w-7xl mx-auto w-full px-4 sm:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-primary">Daftar Guru Relawan</h2>
                <p class="text-xs text-on-surface-variant">Menampilkan {{ $teachers->count() }} dari {{ $teachers->total() }} relawan terverifikasi</p>
            </div>
            @if(request('subject'))
                <span class="bg-secondary-container/50 text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold">
                    Filter: {{ request('subject') }}
                </span>
            @endif
        </div>

        @if($teachers->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-surface-container-high max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-full bg-surface-container-low text-outline mx-auto flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-[32px]">person_search</span>
                </div>
                <h3 class="text-base font-bold text-primary mb-1">Tidak Menemukan Guru Relawan</h3>
                <p class="text-xs text-on-surface-variant mb-4">
                    Belum ada guru dengan kriteria pencarian "{{ request('search') ?? request('subject') ?? request('origin_location') }}". Coba gunakan kata kunci lain.
                </p>
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span> Lihat Semua Guru
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teachers as $teacher)
                    <div class="bg-white rounded-2xl border border-surface-container-high hover:border-secondary transition-all hover:shadow-md flex flex-col justify-between overflow-hidden group">
                        
                        <!-- Top Header Card -->
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="w-14 h-14 rounded-2xl bg-surface-container-high text-primary flex items-center justify-center font-extrabold text-lg flex-shrink-0 group-hover:scale-105 transition-transform shadow-sm">
                                    {{ strtoupper(substr($teacher->user->name ?? 'G', 0, 1)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('teachers.show', $teacher) }}" class="font-bold text-base text-primary hover:underline truncate">
                                            {{ $teacher->user->name }}
                                        </a>
                                        @if($teacher->is_verified)
                                            <span class="material-symbols-outlined text-[18px] text-secondary material-symbols-fill" title="Guru Relawan Terverifikasi">
                                                verified
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-on-surface-variant truncate">
                                        {{ $teacher->institution_origin ?? 'Relawan Pendidikan' }}
                                    </p>
                                    
                                    <!-- Rating Pill -->
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <span class="flex items-center text-amber-500 text-xs font-bold">
                                            <span class="material-symbols-outlined text-[16px] text-amber-500 material-symbols-fill">star</span>
                                            {{ number_format($teacher->rating, 1) }}
                                        </span>
                                        <span class="text-[11px] text-outline">• {{ $teacher->total_reviews }} ulasan</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject & Location Tags -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="bg-surface-container-low text-primary px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">menu_book</span>
                                    {{ $teacher->subject }}
                                </span>
                                <span class="bg-surface-container-low text-on-surface-variant px-2.5 py-1 rounded-lg text-xs font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    {{ $teacher->origin_location }}
                                </span>
                            </div>

                            <!-- Short Bio -->
                            <p class="text-xs text-on-surface-variant mt-3 line-clamp-2 leading-relaxed">
                                {{ $teacher->bio ?? 'Berdedikasi untuk membantu siswa daerah pelosok memahami materi dengan metode santai dan menyenangkan.' }}
                            </p>
                        </div>

                        <!-- Bottom Schedule & Booking Action -->
                        <div class="px-6 py-4 bg-surface-container-low/60 border-t border-surface-container-high flex items-center justify-between gap-3">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-on-surface-variant font-medium">Slot Tersedia</span>
                                <span class="text-xs font-bold text-primary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-secondary">event_available</span>
                                    {{ $teacher->available_schedules_count ?? $teacher->schedules->count() }} Jadwal Luang
                                </span>
                            </div>

                            <a href="{{ route('teachers.show', $teacher) }}" 
                               class="px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                                <span>Pilih Jadwal</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $teachers->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
