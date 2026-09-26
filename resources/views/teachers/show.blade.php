@extends('layouts.app')

@section('title', 'Profil ' . $teacher->user->name . ' — Ajari Aku')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 w-full">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-6">
        <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('teachers.index') }}" class="hover:underline">Cari Guru</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold">{{ $teacher->user->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Sisi Kiri: Profil Detail Guru & Ulasan -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Profil Card -->
            <div class="bg-white rounded-2xl border border-surface-container-high p-6 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-3xl bg-primary-container text-on-primary flex items-center justify-center font-extrabold text-3xl shadow-sm mb-4">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>
                    <div class="flex items-center justify-center gap-1.5">
                        <h1 class="text-xl font-extrabold text-primary">{{ $teacher->user->name }}</h1>
                        @if($teacher->is_verified)
                            <span class="material-symbols-outlined text-secondary material-symbols-fill text-[20px]" title="Relawan Terverifikasi">verified</span>
                        @endif
                    </div>
                    <p class="text-xs text-on-surface-variant font-medium mt-0.5">
                        {{ $teacher->institution_origin ?? 'Relawan Pendidikan Indonesia' }}
                    </p>

                    <!-- Rating Info -->
                    <div class="inline-flex items-center gap-2 bg-surface-container-low px-4 py-1.5 rounded-full mt-3">
                        <span class="flex items-center text-amber-500 font-bold text-sm">
                            <span class="material-symbols-outlined text-amber-500 material-symbols-fill text-[18px] mr-1">star</span>
                            {{ number_format($teacher->rating, 2) }}
                        </span>
                        <span class="text-xs text-outline">• {{ $teacher->total_reviews }} Ulasan Murid</span>
                    </div>

                    <div class="w-full border-t border-surface-container-high my-4"></div>

                    <!-- Meta Tags -->
                    <div class="w-full space-y-2 text-left text-xs">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="flex items-center gap-1.5 text-outline">
                                <span class="material-symbols-outlined text-[16px]">menu_book</span> Spesialisasi
                            </span>
                            <span class="font-bold text-primary">{{ $teacher->subject }}</span>
                        </div>
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span class="flex items-center gap-1.5 text-outline">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> Domisili Guru
                            </span>
                            <span class="font-bold text-primary">{{ $teacher->origin_location }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-outline mb-2">Tentang Guru / Bio</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed bg-surface-container-low p-3.5 rounded-xl">
                        {{ $teacher->bio ?? 'Guru relawan ini siap mendampingi adik-adik di daerah pelosok dengan materi yang disederhanakan dan suasana belajar yang santai.' }}
                    </p>
                </div>
            </div>

            <!-- Ulasan dari Siswa Lain -->
            <div class="bg-white rounded-2xl border border-surface-container-high p-6 shadow-sm">
                <h3 class="text-sm font-bold text-primary mb-4 flex items-center justify-between">
                    <span>Ulasan Murid ({{ $teacher->reviews->count() }})</span>
                    <span class="text-xs text-outline font-normal">Transparan</span>
                </h3>

                @if($teacher->reviews->isEmpty())
                    <p class="text-xs text-outline text-center py-4">Belum ada ulasan untuk guru ini. Jadilah murid pertama yang belajar!</p>
                @else
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-1">
                        @foreach($teacher->reviews as $rev)
                            <div class="border-b border-surface-container-high pb-3 last:border-none">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-primary">{{ $rev->student->name }}</span>
                                    <div class="flex items-center text-amber-500 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                            <span class="material-symbols-outlined text-[14px] {{ $i <= $rev->rating ? 'material-symbols-fill text-amber-500' : 'text-outline-variant' }}">star</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-on-surface-variant">{{ $rev->comment ?? 'Sesi belajar sangat bermanfaat dan mudah dipahami.' }}</p>
                                <span class="text-[10px] text-outline mt-1 block">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Sisi Kanan: Jadwal Ketersediaan & Form Booking Sesi -->
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl border border-surface-container-high p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-2 mb-6">
                    <div>
                        <h2 class="text-lg font-extrabold text-primary">Pilih Jadwal Belajar</h2>
                        <p class="text-xs text-on-surface-variant">Pilih salah satu slot waktu ketersediaan yang dibuka oleh guru relawan ini</p>
                    </div>
                    <span class="bg-secondary-container/50 text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        {{ $teacher->schedules->count() }} Slot Terbuka
                    </span>
                </div>

                @if($teacher->schedules->isEmpty())
                    <div class="text-center py-12 bg-surface-container-low rounded-2xl border border-dashed border-outline-variant">
                        <span class="material-symbols-outlined text-outline text-[40px] mb-2">event_busy</span>
                        <h4 class="text-sm font-bold text-primary">Belum Ada Jadwal Tersedia</h4>
                        <p class="text-xs text-on-surface-variant max-w-sm mx-auto mt-1">
                            Guru relawan ini belum menambahkan slot ketersediaan mengajar baru atau semua slot telah terisi. Silakan cek guru relawan lainnya.
                        </p>
                        <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-1 mt-4 text-xs font-bold text-primary hover:underline">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Cari Guru Lain
                        </a>
                    </div>
                @else
                    <!-- Booking Form -->
                    <form action="{{ route('student.bookings.store') }}" method="POST" id="bookingForm" class="space-y-6">
                        @csrf
                        
                        <!-- Pilihan Slot Radio List -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($teacher->schedules as $index => $sch)
                                <label class="relative flex items-start p-4 rounded-xl border border-surface-container-high hover:border-secondary cursor-pointer transition-all bg-surface-container-lowest hover:bg-surface-container-low/40 has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/20 has-[:checked]:ring-2 has-[:checked]:ring-secondary">
                                    <input type="radio" 
                                           name="schedule_id" 
                                           value="{{ $sch->id }}" 
                                           class="sr-only" 
                                           {{ $index === 0 ? 'checked' : '' }} required>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-extrabold text-primary flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[16px] text-secondary">calendar_today</span>
                                                {{ $sch->start_time->translatedFormat('l, d M Y') }}
                                            </span>
                                            <span class="text-[11px] font-bold text-secondary bg-secondary-container/50 px-2 py-0.5 rounded">
                                                Tersedia
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center justify-between flex-wrap gap-2 text-xs text-on-surface font-semibold">
                                            <div class="flex items-center">
                                                <span class="material-symbols-outlined text-[16px] text-outline mr-1">schedule</span>
                                                {{ $sch->start_time->format('H:i') }} - {{ $sch->end_time->format('H:i') }} WIB
                                            </div>
                                            <span class="realtime-schedule-pill text-[11px] font-bold px-2 py-0.5 rounded-md inline-flex items-center gap-1"
                                                  data-start-time="{{ $sch->start_time->toIso8601String() }}"
                                                  data-end-time="{{ $sch->end_time->toIso8601String() }}"
                                                  data-mode="slot">
                                                <span class="realtime-text font-mono">Memuat...</span>
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Catatan Kebutuhan Belajar dari Siswa -->
                        <div class="space-y-2">
                            <label for="notes" class="block text-xs font-bold text-primary">
                                Catatan Belajar / Materi yang Ingin Dipelajari <span class="text-outline font-normal">(Opsional)</span>
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      placeholder="Contoh: Saya kesulitan memahami cara menghitung pembagian bersusun porogapit..." 
                                      class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3"></textarea>
                            <p class="text-[11px] text-outline">Catatan ini akan langsung dibaca oleh guru relawan sebelum sesi dimulai.</p>
                        </div>

                        <!-- Action Submit Booking -->
                        <div class="p-4 bg-surface-container-low rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-surface-container-high">
                            <div class="text-xs text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary text-[20px]">verified_user</span>
                                <span>Sesi ini <strong>100% Gratis</strong> tanpa biaya apapun.</span>
                            </div>

                            @auth
                                @if(auth()->user()->isStudent())
                                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-md transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">bookmark_add</span>
                                        <span>Konfirmasi & Pesan Sesi</span>
                                    </button>
                                @else
                                    <div class="text-xs text-error font-semibold">
                                        Anda login sebagai guru/admin. Hanya akun Siswa yang dapat memesan sesi.
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-md transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">login</span>
                                    <span>Masuk untuk Memesan</span>
                                </a>
                            @endauth
                        </div>

                    </form>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
