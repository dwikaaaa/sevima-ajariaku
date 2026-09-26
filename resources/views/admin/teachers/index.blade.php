@extends('layouts.admin')

@section('title', 'Verifikasi Guru & Relawan — Admin Panel')
@section('page_title', 'Verifikasi Berkas Relawan')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary">Verifikasi Guru & Relawan</h1>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Pastikan kredensial dan latar belakang pengajar terverifikasi untuk menjamin keamanan siswa di daerah pelosok.
            </p>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Tabs Status Filter -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.teachers.index', ['status' => 'all', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status', 'all') === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    Semua Relawan
                </a>
                <a href="{{ route('admin.teachers.index', ['status' => 'pending', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Menunggu</span>
                    <span class="bg-white/20 text-[10px] px-1.5 rounded-full">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('admin.teachers.index', ['status' => 'approved', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-secondary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Terverifikasi</span>
                    <span class="bg-white/20 text-[10px] px-1.5 rounded-full">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('admin.teachers.index', ['status' => 'rejected', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'rejected' ? 'bg-error text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Ditolak</span>
                    <span class="bg-white/20 text-[10px] px-1.5 rounded-full">{{ $rejectedCount }}</span>
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.teachers.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <div class="relative w-full sm:w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari guru / mapel / kota..." 
                           class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary py-2 pl-8 pr-3">
                    <span class="material-symbols-outlined text-[16px] text-outline absolute left-2.5 top-2.5">search</span>
                </div>
                <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container">
                    Cari
                </button>
            </form>

        </div>
    </div>

    <!-- Table of Teachers -->
    <div class="bg-white rounded-2xl border border-surface-container-high shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-container-low text-outline font-bold border-b border-surface-container-high">
                    <tr>
                        <th class="py-3.5 px-6">Pendidik Relawan</th>
                        <th class="py-3.5 px-6">Mata Pelajaran</th>
                        <th class="py-3.5 px-6">Asal Instansi & Kota</th>
                        <th class="py-3.5 px-6">Status Verifikasi</th>
                        <th class="py-3.5 px-6 text-right">Tindakan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @forelse($teachers as $t)
                        <tr class="hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-surface-container-high text-primary font-extrabold flex items-center justify-center">
                                        {{ strtoupper(substr($t->user->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-primary text-sm block">{{ $t->user->name ?? 'User Terhapus' }}</span>
                                        <span class="text-on-surface-variant text-[11px]">{{ $t->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-surface-container-low px-2.5 py-1 rounded-lg text-primary font-bold inline-block">
                                    {{ $t->subject }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-on-surface block">{{ $t->institution_origin ?? 'Relawan Mandiri' }}</span>
                                <span class="text-on-surface-variant text-[11px] block">📍 {{ $t->origin_location }}</span>
                                @if($t->cv_path)
                                    <a href="{{ $t->cv_path }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-secondary hover:underline font-semibold mt-0.5">
                                        <span class="material-symbols-outlined text-[13px]">link</span>
                                        <span>Lihat Portofolio</span>
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($t->verification_status === 'approved')
                                    <span class="inline-flex items-center gap-1 bg-secondary-container/60 text-on-secondary-container px-2.5 py-1 rounded-full font-bold text-[11px]">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        Terverifikasi
                                    </span>
                                @elseif($t->verification_status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full font-bold text-[11px]">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        Menunggu Persetujuan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-error-container/60 text-error px-2.5 py-1 rounded-full font-bold text-[11px]" title="{{ $t->rejection_reason }}">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.teachers.show', $t) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-surface-container-low hover:bg-surface-container-high text-primary transition-colors">
                                        Detail Berkas
                                    </a>

                                    @if($t->verification_status !== 'approved')
                                        <form action="{{ route('admin.teachers.approve', $t) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-secondary text-white hover:bg-secondary/90 transition-colors shadow-sm">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-outline">
                                Tidak ada data guru relawan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
            <div class="p-4 border-t border-surface-container-high">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
