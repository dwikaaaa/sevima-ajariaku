@extends('layouts.admin')

@section('title', 'Monitoring Sesi Belajar 3T — Admin Panel')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary">Monitoring Sesi Belajar 3T</h1>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Pengawasan langsung terhadap seluruh transaksi sesi pembelajaran dan intervensi darurat tautan video conference.
            </p>
        </div>
    </div>

    <!-- Filter Tabs & Search -->
    <div class="bg-white p-4 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.sessions.index', ['status' => 'all', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status', 'all') === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    Semua Sesi
                </a>
                <a href="{{ route('admin.sessions.index', ['status' => 'approved', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('status') === 'approved' ? 'bg-secondary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Disetujui / Aktif</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalApproved }}</span>
                </a>
                <a href="{{ route('admin.sessions.index', ['status' => 'pending', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Menunggu</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalPending }}</span>
                </a>
                <a href="{{ route('admin.sessions.index', ['status' => 'completed', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('status') === 'completed' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Selesai</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalCompleted }}</span>
                </a>
                <a href="{{ route('admin.sessions.index', ['status' => 'cancelled', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('status') === 'cancelled' ? 'bg-error text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Batal</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalCancelled }}</span>
                </a>
            </div>

            <form action="{{ route('admin.sessions.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <div class="relative w-full sm:w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari murid / guru / mapel..." 
                           class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary py-2 pl-8 pr-3">
                    <span class="material-symbols-outlined text-[16px] text-outline absolute left-2.5 top-2.5">search</span>
                </div>
                <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container">
                    Cari
                </button>
            </form>

        </div>
    </div>

    <!-- Table of Sessions -->
    <div class="bg-white rounded-2xl border border-surface-container-high shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-container-low text-outline font-bold border-b border-surface-container-high">
                    <tr>
                        <th class="py-3.5 px-6">ID & Mapel</th>
                        <th class="py-3.5 px-6">Murid (Daerah 3T)</th>
                        <th class="py-3.5 px-6">Guru Relawan</th>
                        <th class="py-3.5 px-6">Waktu Jadwal</th>
                        <th class="py-3.5 px-6">Status & Tautan Kelas</th>
                        <th class="py-3.5 px-6 text-right">Intervensi Link</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-4 px-6">
                                <span class="text-[10px] text-outline font-bold block">#{{ $b->id }}</span>
                                <span class="font-extrabold text-primary text-sm">{{ $b->teacher->subject }}</span>
                                @if($b->notes)
                                    <span class="text-[11px] text-on-surface-variant line-clamp-1 italic mt-0.5" title="{{ $b->notes }}">
                                        "{{ $b->notes }}"
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-on-surface block">{{ $b->student->name }}</span>
                                <span class="text-secondary font-semibold text-[11px]">
                                    📍 {{ $b->student->studentProfile->region_location ?? 'Pelosok' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-on-surface block">{{ $b->teacher->user->name }}</span>
                                <span class="text-on-surface-variant text-[11px]">{{ $b->teacher->origin_location }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-primary block">
                                    {{ $b->schedule->start_time->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-on-surface-variant text-[11px]">
                                    {{ $b->schedule->start_time->format('H:i') }} - {{ $b->schedule->end_time->format('H:i') }} WIB
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold {{ $b->isApproved() ? 'bg-secondary-container/60 text-on-secondary-container' : ($b->isCompleted() ? 'bg-primary-container/20 text-primary' : ($b->isPending() ? 'bg-amber-100 text-amber-800' : 'bg-error-container/60 text-error')) }}">
                                        {{ ucfirst($b->status) }}
                                    </span>

                                    @if($b->link_meeting)
                                        <a href="{{ $b->link_meeting }}" target="_blank" class="block text-secondary hover:underline font-bold text-[11px] truncate max-w-xs">
                                            🔗 Buka Link Kelas
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <!-- Emergency Update Link Form -->
                                <details class="relative inline-block text-left">
                                    <summary class="list-none px-3 py-1.5 rounded-lg text-xs font-bold bg-surface-container-low hover:bg-surface-container-high text-primary cursor-pointer transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">link</span>
                                        <span>Ganti Link</span>
                                    </summary>

                                    <div class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-surface-container-high p-4 z-20 space-y-2">
                                        <span class="text-xs font-bold text-primary block">Intervensi Link Darurat</span>
                                        <form action="{{ route('admin.sessions.update-link', $b) }}" method="POST" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="url" 
                                                   name="link_meeting" 
                                                   value="{{ $b->link_meeting }}" 
                                                   placeholder="https://meet.jit.si/..." 
                                                   class="w-full text-xs rounded-lg border-surface-container-high p-2" 
                                                   required>
                                            <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-bold bg-primary text-white hover:bg-primary-container transition-colors">
                                                Simpan Tautan
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-outline">
                                Tidak ada data sesi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-surface-container-high">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
