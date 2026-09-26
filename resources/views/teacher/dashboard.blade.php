@extends('layouts.app')

@section('title', 'Dashboard Guru Relawan — Ajari Aku')

@section('content')
<div class="flex flex-col w-full pb-16">

    <!-- Header Strip Profil Guru -->
    <section class="w-full bg-surface-container-low px-4 sm:px-8 py-6 border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-lg sm:text-xl font-extrabold text-primary">{{ $teacher->user->name }}</span>
                        @if($teacher->is_verified)
                            <span class="bg-secondary-container/60 text-on-secondary-container px-2.5 py-0.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] material-symbols-fill text-secondary">verified</span>
                                Relawan Terverifikasi
                            </span>
                        @else
                            <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Menunggu Verifikasi Berkas
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Bidang: <strong>{{ $teacher->subject }}</strong> • Asal: {{ $teacher->origin_location }} • Rating: ⭐ {{ number_format($teacher->rating, 2) }} ({{ $teacher->total_reviews }} Ulasan)
                    </p>
                </div>
            </div>

            <!-- Quick Counter Stats -->
            <div class="flex items-center gap-3">
                <div class="bg-white px-4 py-2 rounded-xl border border-surface-container-high text-center">
                    <span class="block text-[10px] text-outline uppercase font-bold">Murid Menunggu</span>
                    <span class="text-base font-extrabold text-amber-600">{{ $pendingBookings->count() }}</span>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl border border-surface-container-high text-center">
                    <span class="block text-[10px] text-outline uppercase font-bold">Sesi Aktif</span>
                    <span class="text-base font-extrabold text-secondary">{{ $approvedBookings->count() }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-8 py-8 space-y-10">

        <!-- 1. BUKA SLOT JADWAL KETERSEDIAAN BARU & SLOT AKTIF -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Tambah Slot Baru -->
            <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
                <div class="flex items-center gap-2 text-primary font-extrabold text-base">
                    <span class="material-symbols-outlined text-secondary">more_time</span>
                    <h3>Buka Ketersediaan Waktu</h3>
                </div>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Tentukan kapan Anda memiliki waktu luang untuk mengajar adik-adik pelosok (misal: Sabtu, 15:00 - 16:00).
                </p>

                <form action="{{ route('teacher.schedules.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="start_time" class="block text-xs font-bold text-primary mb-1">Waktu Mulai</label>
                        <input type="datetime-local" 
                               name="start_time" 
                               id="start_time" 
                               value="{{ old('start_time') }}" 
                               min="{{ now()->addMinutes(15)->format('Y-m-d\TH:i') }}" 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-2.5" 
                               required>
                    </div>

                    <div>
                        <label for="end_time" class="block text-xs font-bold text-primary mb-1">Waktu Selesai</label>
                        <input type="datetime-local" 
                               name="end_time" 
                               id="end_time" 
                               value="{{ old('end_time') }}" 
                               min="{{ now()->addMinutes(45)->format('Y-m-d\TH:i') }}" 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-2.5" 
                               required>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        <span>Terbitkan Jadwal Luang</span>
                    </button>
                </form>
            </div>

            <!-- List Slot Ketersediaan yang Sedang Dibuka -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">event_available</span>
                        <span>Slot Jadwal Terbuka ({{ $availableSchedules->count() }})</span>
                    </h3>
                    <span class="text-xs text-outline">Menunggu dipilih murid</span>
                </div>

                @if($availableSchedules->isEmpty())
                    <div class="py-12 text-center text-xs text-outline border border-dashed border-surface-container-high rounded-xl">
                        Belum ada slot waktu mengajar yang dibuka. Gunakan formulir di samping untuk membuat jadwal.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-1">
                        @foreach($availableSchedules as $sch)
                            <div class="p-3.5 rounded-xl border border-surface-container-high bg-surface-container-low/40 flex items-center justify-between gap-2">
                                <div>
                                    <span class="text-xs font-bold text-primary block">
                                        {{ $sch->start_time->translatedFormat('l, d M Y') }}
                                    </span>
                                    <span class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[14px] text-outline">schedule</span>
                                        {{ $sch->start_time->format('H:i') }} - {{ $sch->end_time->format('H:i') }} WIB
                                    </span>
                                </div>

                                <form action="{{ route('teacher.schedules.destroy', $sch) }}" method="POST" onsubmit="return confirm('Hapus slot ketersediaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-outline hover:text-error rounded-lg hover:bg-error-container/30 transition-colors" title="Hapus Slot">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </section>

        <!-- 2. PERMOHONAN PEMESANAN DARI MURID (PERLU PERSETUJUAN GURU) -->
        <section class="space-y-4">
            <h3 class="text-base font-extrabold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">assignment_turned_in</span>
                <span>Permohonan Sesi dari Murid ({{ $pendingBookings->count() }})</span>
            </h3>

            @if($pendingBookings->isEmpty())
                <div class="p-8 bg-white rounded-2xl border border-surface-container-high text-center text-xs text-outline">
                    Tidak ada murid yang sedang menunggu persetujuan jadwal.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($pendingBookings as $pb)
                        <div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm space-y-4 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                        Menunggu Persetujuan Anda
                                    </span>
                                    <span class="text-xs text-outline">{{ $pb->created_at->diffForHumans() }}</span>
                                </div>

                                <h4 class="text-base font-extrabold text-primary">{{ $pb->student->name }}</h4>
                                <p class="text-xs text-on-surface-variant font-medium">
                                    Asal: {{ $pb->student->studentProfile->region_location ?? 'Pelosok' }} • {{ $pb->student->studentProfile->grade_level ?? 'Siswa' }} ({{ $pb->student->studentProfile->school_name ?? '-' }})
                                </p>

                                <div class="p-3 bg-surface-container-low rounded-xl text-xs space-y-1">
                                    <div class="font-bold text-primary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-secondary">calendar_month</span>
                                        {{ $pb->schedule->start_time->translatedFormat('l, d F Y (H:i') }} - {{ $pb->schedule->end_time->format('H:i') }} WIB)
                                    </div>
                                    @if($pb->notes)
                                        <p class="text-on-surface-variant italic">"{{ $pb->notes }}"</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Form: Setujui dengan Link Pertemuan / Tolak -->
                            <div class="space-y-3 pt-2 border-t border-surface-container-high">
                                <form action="{{ route('teacher.bookings.approve', $pb) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-[11px] font-bold text-primary mb-1">Tautan Video Conference (Google Meet/Jitsi)</label>
                                        <input type="url" 
                                               name="link_meeting" 
                                               placeholder="Kosongkan jika ingin generate otomatis Jitsi Meet" 
                                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-1.5 px-3">
                                    </div>
                                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold bg-secondary text-white hover:bg-secondary/90 shadow-sm transition-all flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        <span>Setujui Sesi Belajar</span>
                                    </button>
                                </form>

                                <form action="{{ route('teacher.bookings.reject', $pb) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="cancellation_reason" placeholder="Alasan penolakan..." class="flex-1 text-xs rounded-xl border-surface-container-high py-1 px-2.5" required>
                                    <button type="submit" class="px-3 py-1 text-xs font-bold text-error hover:bg-error-container/40 rounded-xl transition-colors">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- 3. SESI AKTIF DISETUJUI & SELESAIKAN KELAS -->
        <section class="space-y-4">
            <h3 class="text-base font-extrabold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">video_camera_front</span>
                <span>Sesi Aktif Siap Ajar ({{ $approvedBookings->count() }})</span>
            </h3>

            @if($approvedBookings->isEmpty())
                <div class="p-8 bg-white rounded-2xl border border-surface-container-high text-center text-xs text-outline">
                    Belum ada sesi aktif yang disetujui.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($approvedBookings as $ab)
                        <div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-surface-container-high pb-4">
                                <div>
                                    <span class="text-xs font-bold text-secondary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">verified</span>
                                        Jadwal Terkonfirmasi
                                    </span>
                                    <h4 class="text-base font-extrabold text-primary mt-0.5">
                                        Murid: {{ $ab->student->name }} ({{ $ab->student->studentProfile->region_location ?? 'Pelosok' }})
                                    </h4>
                                    <p class="text-xs text-on-surface-variant font-medium">
                                        Waktu: {{ $ab->schedule->start_time->translatedFormat('l, d F Y • H:i') }} - {{ $ab->schedule->end_time->format('H:i') }} WIB
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ $ab->link_meeting }}" target="_blank" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                        <span>Buka Ruang Belajar</span>
                                    </a>
                                </div>
                            </div>

                            @php
                                $isEnded = $ab->isScheduleEnded();
                                $endTimeStr = $ab->schedule?->end_time ? $ab->schedule->end_time->format('H:i') : '-';
                            @endphp

                            <!-- Status Sesi & Form Selesaikan Sesi -->
                            @if(! $isEnded)
                                <!-- Sesi Masih Berlangsung / Belum Melewati Waktu Jadwal -->
                                <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-4 space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="flex items-start gap-2.5">
                                            <span class="material-symbols-outlined text-amber-600 text-[22px] flex-shrink-0 mt-0.5">timer</span>
                                            <div>
                                                <h5 class="text-xs font-bold text-amber-950">Sesi Belajar Sedang Berlangsung</h5>
                                                <p class="text-[11px] text-amber-800 mt-0.5">
                                                    Sesuai ketentuan, Anda hanya dapat menandai sesi ini selesai setelah waktu jadwal berakhir pada pukul <strong class="text-amber-950">{{ $endTimeStr }} WIB</strong>.
                                                </p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-amber-200/80 text-amber-900 self-start sm:self-auto flex-shrink-0">
                                            <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                                            Selesai dalam {{ now()->diffForHumans($ab->schedule->end_time, true) }}
                                        </span>
                                    </div>

                                    <!-- Form Disabled State -->
                                    <div class="opacity-60 pointer-events-none filter grayscale-[30%] pt-2 border-t border-amber-200/60">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[16px]">edit_note</span>
                                                Catatan Rangkuman Belajar Murid
                                            </span>
                                            <span class="text-[10px] font-semibold text-amber-800 bg-amber-100 px-2 py-0.5 rounded">
                                                Terkunci sampai {{ $endTimeStr }} WIB
                                            </span>
                                        </div>
                                        <textarea rows="2" 
                                                  disabled 
                                                  placeholder="Formulir rangkuman dan tombol selesai akan otomatis aktif setelah sesi berakhir..." 
                                                  class="w-full text-xs rounded-xl border-gray-300 bg-white/70 p-2.5 cursor-not-allowed"></textarea>
                                        <div class="mt-2">
                                            <button type="button" disabled class="px-4 py-2 rounded-xl text-xs font-bold bg-gray-300 text-gray-500 cursor-not-allowed flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px]">lock_clock</span>
                                                <span>Tandai Selesai (Aktif Setelah {{ $endTimeStr }} WIB)</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Sesi Sudah Melewati Jadwal (Siap Ditandai Selesai) -->
                                <form action="{{ route('teacher.bookings.complete', $ab) }}" method="POST" class="bg-surface-container-low p-4 rounded-xl space-y-3 border border-secondary-container">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <span class="text-xs font-bold text-primary flex items-center gap-1">
                                            <span class="material-symbols-outlined text-secondary text-[16px]">edit_note</span>
                                            Tulis Catatan Rangkuman Belajar Murid (Sesi Selesai Pukul {{ $endTimeStr }} WIB)
                                        </span>
                                        
                                        <label class="flex items-center gap-1.5 text-xs text-on-surface cursor-pointer">
                                            <input type="checkbox" name="student_attendance" value="1" checked class="rounded text-primary focus:ring-primary">
                                            <span>Murid Hadir dalam Sesi</span>
                                        </label>
                                    </div>

                                    <textarea name="summary_notes" 
                                              rows="2" 
                                              placeholder="Tuliskan materi yang telah dipelajari, perkembangan pemahaman murid, dan saran latihan mandiri..." 
                                              class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-2.5 bg-white" 
                                              required></textarea>

                                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-secondary text-white hover:bg-secondary/90 transition-all flex items-center gap-1.5 shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">task_alt</span>
                                        <span>Tandai Selesai & Simpan Catatan Belajar</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

</div>
@endsection
