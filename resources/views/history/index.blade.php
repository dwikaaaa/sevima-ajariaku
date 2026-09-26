@extends('layouts.app')

@section('title', 'Histori Pembelajaran — Ajari Aku')

@section('content')
<div class="flex flex-col w-full pb-16">

    <!-- Header Strip -->
    <section class="w-full bg-surface-container-low px-4 sm:px-8 py-6 border-b border-surface-container-high">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[30px]">history_edu</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-primary">Histori Pembelajaran</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $user->isTeacher() ? 'bg-secondary-container text-on-secondary-container' : ($user->isStudent() ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-primary-container text-on-primary') }}">
                            {{ $user->isTeacher() ? 'Rekam Jejak Mengajar' : ($user->isStudent() ? 'Riwayat Belajarku' : 'Sesi Nusantara') }}
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5 max-w-2xl">
                        @if($user->isTeacher())
                            Rekam jejak dedikasi mengajar Anda untuk adik-adik di daerah pelosok, catatan evaluasi belajar, dan ulasan apresiasi dari murid.
                        @elseif($user->isStudent())
                            Arsip lengkap seluruh sesi belajar daring yang pernah Anda ikuti, rangkuman materi dari guru relawan, serta status kehadiran.
                        @else
                            Monitoring dan pengawasan riwayat sesi belajar yang telah dijadwalkan dan diselesaikan di seluruh penjuru 3T.
                        @endif
                    </p>
                </div>
            </div>

            <div>
                @php
                    $backUrl = $user->isStudent() ? route('student.dashboard') : ($user->isTeacher() ? route('teacher.dashboard') : route('admin.dashboard'));
                @endphp
                <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-primary bg-white border border-surface-container-high hover:bg-surface-container-low transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-8 py-8 space-y-8">

        <!-- 1. Statistik Pembelajaran Ringkas (Bento Grid) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">verified</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-outline uppercase tracking-wider">Sesi Selesai</span>
                    <span class="text-xl font-extrabold text-primary">{{ $stats['completed'] }}</span>
                    <span class="text-[10px] text-on-surface-variant block">Sesi pembelajaran tuntas</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/40 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">schedule</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-outline uppercase tracking-wider">Akumulasi Waktu</span>
                    <span class="text-xl font-extrabold text-primary">{{ $stats['total_hours'] }} Jam</span>
                    <span class="text-[10px] text-on-surface-variant block">Total durasi bimbingan</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">co_present</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-outline uppercase tracking-wider">Kehadiran Murid</span>
                    <span class="text-xl font-extrabold text-primary">{{ $stats['attendance_rate'] }}%</span>
                    <span class="text-[10px] text-on-surface-variant block">{{ $stats['attended_count'] }} sesi hadir tepat waktu</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container-low text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-outline uppercase tracking-wider">Total Booking</span>
                    <span class="text-xl font-extrabold text-primary">{{ $stats['total'] }}</span>
                    <span class="text-[10px] text-on-surface-variant block">Riwayat seluruh permohonan</span>
                </div>
            </div>

        </div>

        <!-- 2. Filter Status Navigasi -->
        <div class="bg-surface-container-low p-1.5 rounded-2xl border border-surface-container-high flex flex-wrap items-center gap-1.5 shadow-sm">
            <a href="{{ route('history.index', ['status' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'all' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span>Semua Sesi</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">{{ $stats['total'] }}</span>
            </a>

            <a href="{{ route('history.index', ['status' => 'completed']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'completed' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[15px]">verified</span>
                <span>Selesai</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $status === 'completed' ? 'bg-white/20 text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">{{ $stats['completed'] }}</span>
            </a>

            <a href="{{ route('history.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'approved' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[15px]">video_camera_front</span>
                <span>Siap Ajar / Aktif</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $status === 'approved' ? 'bg-white/20 text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">{{ $stats['approved'] }}</span>
            </a>

            <a href="{{ route('history.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[15px]">pending_actions</span>
                <span>Menunggu Persetujuan</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $status === 'pending' ? 'bg-white/20 text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">{{ $stats['pending'] }}</span>
            </a>

            <a href="{{ route('history.index', ['status' => 'cancelled']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $status === 'cancelled' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[15px]">cancel</span>
                <span>Dibatalkan / Ditolak</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $status === 'cancelled' ? 'bg-white/20 text-white' : 'bg-surface-container-highest text-on-surface-variant' }}">{{ $stats['cancelled'] }}</span>
            </a>
        </div>

        <!-- 3. Daftar Histori Sesi Belajar -->
        @if($bookings->isEmpty())
            <div class="bg-white p-12 rounded-3xl border border-surface-container-high text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-low text-outline flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-[36px]">event_busy</span>
                </div>
                <h3 class="text-base font-extrabold text-primary">Tidak Ada Riwayat Sesi</h3>
                <p class="text-xs text-on-surface-variant max-w-md mx-auto">
                    Belum ditemukan data histori pembelajaran untuk filter ini. Sesi yang telah dijadwalkan atau diselesaikan akan muncul di sini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('history.index') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary/90 transition-all">
                        <span>Tampilkan Semua Sesi</span>
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white p-6 rounded-3xl border border-surface-container-high shadow-sm space-y-4 transition-all hover:border-secondary/40">
                        
                        <!-- Header Baris Sesi -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-surface-container-high pb-4">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    
                                    <!-- Badge Status Sesi -->
                                    @if($booking->isCompleted())
                                        <span class="bg-emerald-100 text-emerald-800 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Selesai Belajar
                                        </span>
                                        @if($booking->student_attendance)
                                            <span class="bg-secondary-container/50 text-on-secondary-container text-[11px] font-bold px-2 py-0.5 rounded-full">
                                                Murid Hadir
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-[11px] font-bold px-2 py-0.5 rounded-full">
                                                Murid Tidak Hadir
                                            </span>
                                        @endif
                                    @elseif($booking->isApproved())
                                        <span class="bg-blue-100 text-blue-800 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">video_camera_front</span>
                                            Sesi Disetujui (Siap Belajar)
                                        </span>
                                    @elseif($booking->isPending())
                                        <span class="bg-amber-100 text-amber-800 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                            Menunggu Persetujuan Guru
                                        </span>
                                    @elseif($booking->isCancelled())
                                        <span class="bg-red-100 text-red-800 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                                            Dibatalkan / Ditolak
                                        </span>
                                    @endif

                                </div>

                                <h3 class="text-base font-extrabold text-primary">
                                    @if($user->isTeacher())
                                        Sesi bersama Murid: {{ $booking->student->name ?? 'Siswa' }}
                                    @else
                                        {{ $booking->teacher->subject ?? 'Sesi Belajar' }} bersama {{ $booking->teacher->user->name ?? 'Guru Relawan' }}
                                    @endif
                                </h3>

                                <p class="text-xs text-on-surface-variant font-medium mt-0.5">
                                    @if($user->isTeacher())
                                        Asal: {{ $booking->student->studentProfile->region_location ?? 'Pelosok' }} • {{ $booking->student->studentProfile->grade_level ?? 'Siswa' }} ({{ $booking->student->studentProfile->school_name ?? '-' }})
                                    @else
                                        Bidang: {{ $booking->teacher->subject ?? 'Umum' }} • Asal Guru: {{ $booking->teacher->origin_location ?? '-' }}
                                    @endif
                                </p>
                            </div>

                            <!-- Waktu Jadwal -->
                            <div class="text-left sm:text-right flex flex-col sm:items-end">
                                <div class="text-xs font-bold text-primary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">calendar_month</span>
                                    <span>{{ $booking->schedule ? $booking->schedule->start_time->translatedFormat('l, d F Y') : '-' }}</span>
                                </div>
                                <div class="text-[11px] text-on-surface-variant font-semibold mt-0.5">
                                    @if($booking->schedule)
                                        Pukul {{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->schedule->end_time->format('H:i') }} WIB
                                    @endif
                                </div>
                                @if($booking->completed_at)
                                    <span class="text-[10px] text-outline mt-1">
                                        Diselesaikan: {{ $booking->completed_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan & Materi Rangkuman Pembelajaran -->
                        @if($booking->summary_notes)
                            <div class="bg-surface-container-low p-4 rounded-2xl border border-surface-container-high space-y-1">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-primary">
                                    <span class="material-symbols-outlined text-secondary text-[16px]">edit_note</span>
                                    <span>Rangkuman Materi & Catatan Guru Relawan:</span>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    {{ $booking->summary_notes }}
                                </p>
                            </div>
                        @endif

                        @if($booking->notes)
                            <div class="text-xs text-on-surface-variant bg-surface-container-lowest p-3 rounded-xl border border-surface-container-high">
                                <span class="font-bold text-primary">Catatan Kebutuhan Murid:</span> "{{ $booking->notes }}"
                            </div>
                        @endif

                        @if($booking->cancellation_reason)
                            <div class="text-xs text-red-800 bg-red-50 p-3 rounded-xl border border-red-200">
                                <span class="font-bold">Alasan Penolakan/Pembatalan:</span> {{ $booking->cancellation_reason }}
                            </div>
                        @endif

                        <!-- Ulasan & Rating / Link Sesi Belajar -->
                        <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <!-- Bagian Ulasan -->
                            @if($booking->review)
                                <div class="text-xs flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-primary">Ulasan Murid:</span>
                                    <div class="flex items-center text-amber-500">
                                        @for($i=1; $i<=5; $i++)
                                            <span class="material-symbols-outlined text-[16px] {{ $i <= $booking->review->rating ? 'material-symbols-fill text-amber-500' : 'text-outline-variant' }}">star</span>
                                        @endfor
                                    </div>
                                    <span class="text-on-surface-variant italic">"{{ $booking->review->comment }}"</span>
                                </div>
                            @elseif($booking->canBeReviewed() && $user->isStudent())
                                <form action="{{ route('student.bookings.review', $booking) }}" method="POST" class="w-full flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-primary flex-shrink-0">Beri Rating:</span>
                                        <select name="rating" class="text-xs rounded-xl border-surface-container-high focus:ring-primary focus:border-primary py-1.5" required>
                                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                                            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                            <option value="2">⭐⭐ (2 - Kurang)</option>
                                            <option value="1">⭐ (1 - Buruk)</option>
                                        </select>
                                    </div>
                                    <input type="text" name="comment" placeholder="Tulis ulasan/ucapan terima kasih untuk guru..." class="flex-1 text-xs rounded-xl border-surface-container-high focus:ring-primary focus:border-primary py-1.5 px-3">
                                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-sm transition-all flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">send</span>
                                        <span>Kirim Ulasan</span>
                                    </button>
                                </form>
                            @else
                                <div class="text-xs text-outline">
                                    {{ $booking->isCompleted() ? 'Belum ada ulasan yang ditinggalkan.' : '' }}
                                </div>
                            @endif

                            <!-- Tombol Link Ruang Belajar (Jika Sesi Aktif) -->
                            @if($booking->isApproved() && $booking->link_meeting)
                                <a href="{{ $booking->link_meeting }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-secondary text-white hover:bg-secondary/90 shadow-sm transition-all self-start sm:self-auto">
                                    <span class="material-symbols-outlined text-[16px]">video_camera_front</span>
                                    <span>Buka Ruang Belajar</span>
                                </a>
                            @endif

                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pt-4 flex justify-center">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
