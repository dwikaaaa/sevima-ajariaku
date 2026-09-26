@extends('layouts.admin')

@section('title', 'Manajemen Pengguna — Admin Panel')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary">Manajemen Pengguna</h1>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Kelola data akun siswa pelosok, guru relawan, dan administrator untuk menjaga ekosistem platform tetap tertib.
            </p>
        </div>

        <div class="flex items-center gap-3 text-xs">
            <span class="bg-white px-3 py-1.5 rounded-xl border border-surface-container-high font-bold text-primary">
                Total: {{ $totalAll }} Pengguna Terdaftar
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-surface-container-high shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Role Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.users.index', ['role' => 'all', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('role', 'all') === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    Semua ({{ $totalAll }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'siswa', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('role') === 'siswa' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Siswa 3T</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalStudents }}</span>
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'guru', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ request('role') === 'guru' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container-high' }}">
                    <span>Guru Relawan</span>
                    <span class="text-[10px] bg-white/20 px-1.5 rounded-full">{{ $totalTeachers }}</span>
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="role" value="{{ request('role', 'all') }}">
                <div class="relative w-full sm:w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama atau email..." 
                           class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary py-2 pl-8 pr-3">
                    <span class="material-symbols-outlined text-[16px] text-outline absolute left-2.5 top-2.5">search</span>
                </div>
                <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container">
                    Cari
                </button>
            </form>

        </div>
    </div>

    <!-- Table of Users -->
    <div class="bg-white rounded-2xl border border-surface-container-high shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-container-low text-outline font-bold border-b border-surface-container-high">
                    <tr>
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-6">Peran (Role)</th>
                        <th class="py-3.5 px-6">Konteks / Wilayah Asal</th>
                        <th class="py-3.5 px-6">Tanggal Bergabung</th>
                        <th class="py-3.5 px-6 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @forelse($users as $u)
                        <tr class="hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl font-bold flex items-center justify-center {{ $u->isTeacher() ? 'bg-secondary-container text-on-secondary-container' : ($u->isStudent() ? 'bg-primary-container text-white' : 'bg-surface-container-high text-primary') }}">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-primary text-sm block">{{ $u->name }}</span>
                                        <span class="text-on-surface-variant text-[11px]">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($u->isTeacher())
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-secondary-container/50 text-on-secondary-container">
                                        Guru Relawan
                                    </span>
                                @elseif($u->isStudent())
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-primary-container/10 text-primary">
                                        Murid 3T
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 text-amber-800">
                                        Administrator
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($u->isStudent())
                                    <span class="text-on-surface font-semibold block">
                                        📍 {{ $u->studentProfile->region_location ?? 'Pelosok' }}
                                    </span>
                                    <span class="text-[11px] text-outline">
                                        {{ $u->studentProfile->grade_level ?? 'Siswa' }} • {{ $u->studentProfile->school_name ?? '-' }}
                                    </span>
                                @elseif($u->isTeacher())
                                    <span class="text-on-surface font-semibold block">
                                        📍 {{ $u->teacherProfile->origin_location ?? '-' }}
                                    </span>
                                    <span class="text-[11px] text-outline">
                                        {{ $u->teacherProfile->subject ?? '-' }} ({{ $u->teacherProfile->institution_origin ?? '-' }})
                                    </span>
                                @else
                                    <span class="text-outline">Sistem Utama</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-on-surface-variant">{{ $u->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($u->id !== auth()->id())
                                    <!-- Tombol Hapus Akun Pelanggaran -->
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-error hover:bg-error-container/40 transition-colors inline-flex items-center gap-1" title="Hapus Akun Pelanggaran">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-outline text-[11px]">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-outline">
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-surface-container-high">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
