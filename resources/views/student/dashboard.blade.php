@extends('layouts.app')

@section('title', 'Ruang Belajarku — Dashboard Siswa')

@section('content')
<div class="flex flex-col w-full pb-16">

    <!-- Welcome / Student Profile Header Strip -->
    <section class="w-full bg-surface-container-low px-4 sm:px-8 py-6 border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-lg sm:text-xl font-extrabold text-primary">Halo, {{ $student->name }}! 👋</span>
                        <span class="bg-tertiary-fixed text-on-tertiary-fixed px-2.5 py-0.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] material-symbols-fill">stars</span>
                            Pelajar Pelosok Nusantara
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        {{ $student->studentProfile->school_name ?? 'Sekolah Daerah Terpencil' }} • {{ $student->studentProfile->grade_level ?? 'Siswa' }} • Asal: {{ $student->studentProfile->region_location ?? 'Indonesia' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('teachers.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                    <span>Pesan Sesi Baru</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Workspace -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-8 py-8 space-y-8">

        <!-- 1. SESI AKTIF BERIKUTNYA (Hero Bento) -->
        @php
            $nextSession = $upcomingSessions->first();
        @endphp

        @if($nextSession)
            <section class="bg-surface rounded-3xl overflow-hidden shadow-sm border border-secondary/30 relative">
                <div class="h-2 w-full bg-secondary"></div>
                <div class="p-6 sm:p-8 flex flex-col lg:flex-row gap-6 justify-between items-start lg:items-center">
                    
                    <div class="space-y-3 flex-1">
                        <div class="inline-flex items-center gap-2 bg-secondary-container px-3.5 py-1 rounded-full text-on-secondary-container text-xs font-extrabold tracking-wide uppercase">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-secondary"></span>
                            </span>
                            <span>SESI SIAP DIMULAI • JADWAL DISETUJUI</span>
                        </div>

                        <h2 class="text-2xl font-extrabold text-primary">
                            {{ $nextSession->teacher->subject }} bersama {{ $nextSession->teacher->user->name }}
                        </h2>

                        <div class="flex items-center gap-4 text-xs text-on-surface-variant font-medium">
                            <span class="flex items-center gap-1.5 text-primary font-bold">
                                <span class="material-symbols-outlined text-[18px] text-secondary">calendar_today</span>
                                {{ $nextSession->schedule->start_time->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="flex items-center gap-1.5 text-primary font-bold">
                                <span class="material-symbols-outlined text-[18px] text-secondary">schedule</span>
                                {{ $nextSession->schedule->start_time->format('H:i') }} - {{ $nextSession->schedule->end_time->format('H:i') }} WIB
                            </span>
                        </div>

                        @if($nextSession->notes)
                            <p class="text-xs text-on-surface-variant bg-white p-3 rounded-xl border border-surface-container-high max-w-xl">
                                <strong>Catatan belajarmu:</strong> "{{ $nextSession->notes }}"
                            </p>
                        @endif
                    </div>

                    <!-- Action Button: Link Video Conference -->
                    <div class="flex flex-col items-stretch sm:items-end gap-2 w-full lg:w-auto">
                        @if($nextSession->link_meeting)
                            <a href="{{ $nextSession->link_meeting }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="px-8 py-4 rounded-2xl text-sm font-extrabold bg-secondary text-white hover:bg-secondary/90 shadow-md transition-all flex items-center justify-center gap-2 hover:scale-[1.02] text-center">
                                <span class="material-symbols-outlined text-[22px]">video_camera_front</span>
                                <span>Masuk Ruang Belajar Sekarang</span>
                            </a>
                            <span class="text-[11px] text-outline text-center">Klik tautan di atas untuk langsung membuka tatap muka daring</span>
                        @else
                            <div class="p-3 bg-white rounded-xl border border-surface-container-high text-xs text-outline text-center">
                                Menunggu tautan ruang pertemuan dari guru...
                            </div>
                        @endif
                    </div>

                </div>
            </section>
        @endif

        <!-- 2. DAFTAR SESI MENUNGGU KONFIRMASI GURU -->
        <section class="space-y-4">
            <h3 class="text-base font-extrabold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">pending_actions</span>
                <span>Sesi Menunggu Persetujuan Guru ({{ $pendingSessions->count() }})</span>
            </h3>

            @if($pendingSessions->isEmpty())
                <div class="p-6 bg-white rounded-2xl border border-surface-container-high text-center text-xs text-outline">
                    Tidak ada permohonan sesi belajar yang sedang menunggu.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingSessions as $p)
                        <div class="p-5 bg-white rounded-2xl border border-surface-container-high shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                        Menunggu Persetujuan
                                    </span>
                                    <span class="text-xs text-outline">{{ $p->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-primary">{{ $p->teacher->subject }}</h4>
                                <p class="text-xs text-on-surface-variant font-medium">Guru: {{ $p->teacher->user->name }} ({{ $p->teacher->origin_location }})</p>
                                
                                <div class="mt-3 text-xs text-on-surface flex items-center gap-1 font-semibold">
                                    <span class="material-symbols-outlined text-[15px] text-secondary">event</span>
                                    {{ $p->schedule->start_time->translatedFormat('d M Y, H:i') }} WIB
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- 3. RIWAYAT SESI SELESAI & EVALUASI GURU -->
        <section class="space-y-4">
            <h3 class="text-base font-extrabold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">task_alt</span>
                <span>Riwayat Selesai & Catatan Guru ({{ $completedSessions->count() }})</span>
            </h3>

            @if($completedSessions->isEmpty())
                <div class="p-6 bg-white rounded-2xl border border-surface-container-high text-center text-xs text-outline">
                    Belum ada sesi belajar yang diselesaikan.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($completedSessions as $c)
                        <div class="p-6 bg-white rounded-2xl border border-surface-container-high shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-surface-container-high pb-3">
                                <div>
                                    <h4 class="text-sm font-bold text-primary">{{ $c->teacher->subject }} bersama {{ $c->teacher->user->name }}</h4>
                                    <span class="text-xs text-outline">Selesai pada: {{ $c->completed_at ? $c->completed_at->translatedFormat('d F Y, H:i') : $c->updated_at->translatedFormat('d F Y') }}</span>
                                </div>
                                <span class="bg-secondary-container/50 text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold self-start">
                                    Selesai
                                </span>
                            </div>

                            <!-- Summary Notes dari Guru -->
                            @if($c->summary_notes)
                                <div class="bg-surface-container-low p-4 rounded-xl border border-surface-container-high">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-primary mb-1">
                                        <span class="material-symbols-outlined text-secondary text-[16px]">edit_note</span>
                                        <span>Rangkuman Belajar & Pesan Guru:</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">{{ $c->summary_notes }}</p>
                                </div>
                            @endif

                            <!-- Form Review Jika Belum Diulas -->
                            <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                @if($c->review)
                                    <div class="text-xs text-outline flex items-center gap-1.5">
                                        <span>Ulasan Anda:</span>
                                        <div class="flex items-center text-amber-500">
                                            @for($i=1; $i<=5; $i++)
                                                <span class="material-symbols-outlined text-[14px] {{ $i <= $c->review->rating ? 'material-symbols-fill text-amber-500' : 'text-outline-variant' }}">star</span>
                                            @endfor
                                        </div>
                                        <span>"{{ $c->review->comment }}"</span>
                                    </div>
                                @elseif($c->canBeReviewed())
                                    <form action="{{ route('student.bookings.review', $c) }}" method="POST" class="w-full flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-primary">Beri Rating:</span>
                                            <select name="rating" class="text-xs rounded-lg border-surface-container-high focus:ring-primary focus:border-primary py-1" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                                                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                                <option value="2">⭐⭐ (2 - Kurang)</option>
                                                <option value="1">⭐ (1 - Buruk)</option>
                                            </select>
                                        </div>
                                        <input type="text" name="comment" placeholder="Tulis ulasan/ucapan terima kasih untuk guru..." class="flex-1 text-xs rounded-lg border-surface-container-high focus:ring-primary focus:border-primary py-1 px-3">
                                        <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-primary text-white hover:bg-primary-container transition-all">
                                            Kirim Ulasan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

</div>
@endsection
