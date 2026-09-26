@extends('layouts.admin')

@section('title', 'Tinjau Berkas Relawan: ' . $teacher->user->name . ' — Admin Panel')
@section('page_title', 'Tinjau Berkas Relawan')

@section('content')
<div class="max-w-4xl space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-outline">
        <a href="{{ route('admin.teachers.index') }}" class="hover:underline">Daftar Verifikasi</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold">{{ $teacher->user->name }}</span>
    </div>

    <!-- Main Card Tinjau Berkas -->
    <div class="bg-white rounded-2xl border border-surface-container-high p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-surface-container-high pb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary-container text-white flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-primary">{{ $teacher->user->name }}</h1>
                    <p class="text-xs text-on-surface-variant font-medium">{{ $teacher->user->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($teacher->verification_status === 'approved')
                            <span class="bg-secondary-container/60 text-on-secondary-container px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Terverifikasi Aktif
                            </span>
                        @elseif($teacher->verification_status === 'pending')
                            <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Menunggu Persetujuan Admin
                            </span>
                        @else
                            <span class="bg-error-container/60 text-error px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Ditolak
                            </span>
                        @endif
                        <span class="text-xs text-outline">• Mendaftar {{ $teacher->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Header -->
            <div class="flex items-center gap-2">
                @if($teacher->verification_status !== 'approved')
                    <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-secondary text-white hover:bg-secondary/90 shadow-sm transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            <span>Setujui Guru Ini</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Detail Data Relawan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <span class="text-outline text-xs block font-bold uppercase tracking-wider mb-1">Mata Pelajaran Spesialisasi</span>
                    <span class="text-sm font-extrabold text-primary bg-surface-container-low px-3 py-1.5 rounded-xl inline-block">
                        {{ $teacher->subject }}
                    </span>
                </div>

                <div>
                    <span class="text-outline text-xs block font-bold uppercase tracking-wider mb-1">Domisili / Kota Asal</span>
                    <span class="text-sm font-semibold text-on-surface">📍 {{ $teacher->origin_location }}</span>
                </div>

                <div>
                    <span class="text-outline text-xs block font-bold uppercase tracking-wider mb-1">Institusi / Universitas / Organisasi</span>
                    <span class="text-sm font-semibold text-on-surface">{{ $teacher->institution_origin ?? 'Tidak dicantumkan' }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="text-outline text-xs block font-bold uppercase tracking-wider mb-1">Biodata & Motivasi Relawan</span>
                    <p class="text-xs text-on-surface-variant leading-relaxed bg-surface-container-low p-4 rounded-xl">
                        {{ $teacher->bio ?? 'Relawan belum menuliskan biodata lengkap.' }}
                    </p>
                </div>

                @if($teacher->rejection_reason)
                    <div class="p-4 bg-error-container/40 border border-error text-error rounded-xl">
                        <span class="text-xs font-bold block mb-1">Catatan Penolakan Sebelumnya:</span>
                        <p class="text-xs">{{ $teacher->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tinjauan Berkas Portofolio / CV Relawan -->
        <div class="bg-surface-container-low/70 border border-surface-container-high rounded-2xl p-5 sm:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">description</span>
                    </div>
                    <div>
                        <span class="text-sm font-extrabold text-primary block">Tautan Portofolio / CV Relawan</span>
                        <span class="text-xs text-on-surface-variant">Dokumen bukti kelayakan dan rekam jejak calon guru relawan</span>
                    </div>
                </div>

                @if($teacher->cv_path)
                    <a href="{{ $teacher->cv_path }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-sm transition-all self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        <span>Buka Tautan Portofolio / CV</span>
                    </a>
                @else
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-surface-container-high text-outline inline-flex items-center gap-1.5 self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[16px]">link_off</span>
                        <span>Belum Mengisi Tautan Portofolio</span>
                    </span>
                @endif
            </div>

            @if($teacher->cv_path)
                <div class="bg-white p-3.5 rounded-xl border border-surface-container-high flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="material-symbols-outlined text-[18px] text-secondary shrink-0">link</span>
                        <span class="font-mono text-xs truncate text-primary font-bold select-all">{{ $teacher->cv_path }}</span>
                    </div>
                    <a href="{{ $teacher->cv_path }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="text-secondary hover:underline font-bold text-xs shrink-0 flex items-center gap-1 self-end sm:self-auto">
                        <span>Buka Dokumen</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            @else
                <p class="text-xs text-amber-800 bg-amber-50 border border-amber-200 p-3 rounded-xl">
                    ⚠️ Pengajar ini belum mencantumkan tautan portofolio/CV. Harap hubungi yang bersangkutan sebelum menyetujui verifikasi.
                </p>
            @endif
        </div>

        <!-- Formulir Penolakan Jika Berkas Tidak Memenuhi Syarat -->
        @if($teacher->verification_status !== 'rejected')
            <div class="pt-6 border-t border-surface-container-high">
                <h3 class="text-xs font-bold text-error uppercase tracking-wider mb-2">Tolak Verifikasi Berkas</h3>
                <form action="{{ route('admin.teachers.reject', $teacher) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <textarea name="rejection_reason" 
                                  rows="2" 
                                  placeholder="Tulis alasan penolakan (misal: identitas tidak jelas, profil terindikasi spam)..." 
                                  class="w-full text-xs rounded-xl border-surface-container-high focus:border-error focus:ring-error p-3" 
                                  required></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Yakin ingin menolak verifikasi guru ini?')" class="px-4 py-2 rounded-xl text-xs font-bold bg-error text-white hover:bg-error/90 transition-colors">
                        Tolak Verifikasi
                    </button>
                </form>
            </div>
        @endif

    </div>

</div>
@endsection
