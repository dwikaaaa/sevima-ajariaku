@extends('layouts.admin')

@section('title', 'Pusat Kendali Operasional 3T — Dashboard Admin')

@section('content')
<div class="space-y-8">

    <!-- Header Judul & Tombol Aksi Cepat -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary">Pusat Kendali & Metrik Dampak Edukasi</h1>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Gambaran statistik menyeluruh pemerataan pendidikan relawan untuk siswa daerah 3T di seluruh Indonesia.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.teachers.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-500 text-white hover:bg-amber-600 transition-colors shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span>{{ $pendingTeachersCount }} Guru Perlu Verifikasi</span>
            </a>
        </div>
    </div>

    <!-- 1. Executive Summary & Impact Metrics (Kartu Metrik Presentasi Juri) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Metric 1: Guru Relawan -->
        <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-outline uppercase tracking-wider">Total Guru Relawan</span>
                <div class="w-9 h-9 rounded-xl bg-surface-container-low text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">person_check</span>
                </div>
            </div>
            <div class="my-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-extrabold text-primary">{{ $totalTeachers }}</span>
                    <span class="text-xs text-on-surface-variant font-semibold">Pendidik Aktif</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-secondary font-bold">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>{{ $pendingTeachersCount }} Menunggu Verifikasi</span>
                </div>
            </div>
            <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-secondary h-full rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <!-- Metric 2: Murid 3T -->
        <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-outline uppercase tracking-wider">Murid Terbantu di 3T</span>
                <div class="w-9 h-9 rounded-xl bg-surface-container-low text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">local_library</span>
                </div>
            </div>
            <div class="my-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-extrabold text-primary">{{ $totalStudents }}</span>
                    <span class="text-xs text-on-surface-variant font-semibold">Siswa Pelosok</span>
                </div>
                <p class="text-[11px] text-on-surface-variant mt-1">Tersebar di desa-desa pulau terluar</p>
            </div>
            <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-secondary h-full rounded-full" style="width: 78%"></div>
            </div>
        </div>

        <!-- Metric 3: Sesi Belajar Berhasil -->
        <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-outline uppercase tracking-wider">Sesi Belajar Selesai</span>
                <div class="w-9 h-9 rounded-xl bg-surface-container-low text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                </div>
            </div>
            <div class="my-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-extrabold text-primary">{{ $totalCompletedSessions }}</span>
                    <span class="text-xs text-on-surface-variant font-semibold">Sesi Tuntas</span>
                </div>
                <div class="flex items-center gap-1 mt-1 text-[11px] text-secondary font-semibold">
                    <span class="material-symbols-outlined text-[14px]">timelapse</span>
                    <span>~{{ $totalLearningHours }} Jam Pembelajaran</span>
                </div>
            </div>
            <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-secondary h-full rounded-full" style="width: 92%"></div>
            </div>
        </div>

        <!-- Metric 4: Kepuasan Belajar -->
        <div class="bg-white p-5 rounded-2xl border border-surface-container-high shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-outline uppercase tracking-wider">Tingkat Kepuasan</span>
                <div class="w-9 h-9 rounded-xl bg-surface-container-low text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px] material-symbols-fill">star</span>
                </div>
            </div>
            <div class="my-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-extrabold text-primary">{{ $satisfactionRate }}%</span>
                    <span class="text-xs text-on-surface-variant font-semibold">Skor Evaluasi</span>
                </div>
                <p class="text-[11px] text-on-surface-variant mt-1">Rata-rata: ⭐ {{ number_format($avgRating, 2) }} / 5.00</p>
            </div>
            <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-secondary h-full rounded-full" style="width: 98%"></div>
            </div>
        </div>

    </div>

    <!-- 2. Live Monitoring Radar: Sesi Belajar Langsung di Wilayah 3T -->
    <div class="bg-white rounded-2xl border border-surface-container-high shadow-sm overflow-hidden">
        <div class="bg-primary-container p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-white">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary-container text-[24px]">sensors</span>
                <div>
                    <h2 class="text-base font-extrabold">Monitoring Sesi Belajar di Sistem (Radar 3T)</h2>
                    <p class="text-xs text-surface-dim mt-0.5">Pantau kesiapan murid, guru, serta keaktifan tautan video conference kelas.</p>
                </div>
            </div>
            <a href="{{ route('admin.sessions.index') }}" class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold transition-colors self-start sm:self-auto">
                Lihat Semua Sesi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-container-low text-outline font-bold border-b border-surface-container-high">
                    <tr>
                        <th class="py-3.5 px-6">ID & Mapel</th>
                        <th class="py-3.5 px-6">Guru Relawan</th>
                        <th class="py-3.5 px-6">Murid & Wilayah 3T</th>
                        <th class="py-3.5 px-6">Jadwal & Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi Intervensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @forelse($activeSessions as $ses)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="py-4 px-6">
                                <span class="text-[10px] font-bold text-outline block">#{{ $ses->id }}</span>
                                <span class="font-extrabold text-primary text-sm">{{ $ses->teacher->subject }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-on-surface block">{{ $ses->teacher->user->name }}</span>
                                <span class="text-on-surface-variant text-[11px]">{{ $ses->teacher->origin_location }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-on-surface block">{{ $ses->student->name }}</span>
                                <span class="text-secondary font-semibold text-[11px]">
                                    📍 {{ $ses->student->studentProfile->region_location ?? 'Daerah Pelosok' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-primary block">
                                    {{ $ses->schedule->start_time->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold mt-1 {{ $ses->isApproved() ? 'bg-secondary-container/60 text-on-secondary-container' : 'bg-amber-100 text-amber-800' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ses->isApproved() ? 'bg-secondary' : 'bg-amber-600' }}"></span>
                                    {{ ucfirst($ses->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($ses->link_meeting)
                                    <a href="{{ $ses->link_meeting }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-surface-container-low hover:bg-surface-container-high text-primary font-bold text-[11px] inline-flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">videocam</span>
                                        <span>Cek Link Kelas</span>
                                    </a>
                                @else
                                    <span class="text-outline text-[11px]">Belum Ada Link</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-outline">
                                Belum ada sesi aktif yang tercatat di sistem saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Tabel Cepat Guru yang Menunggu Verifikasi Berkas -->
    @if($pendingTeachers->isNotEmpty())
        <div class="bg-white rounded-2xl border border-surface-container-high p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">pending</span>
                    <h3 class="text-base font-extrabold text-primary">Guru Baru Menunggu Persetujuan Berkas</h3>
                </div>
                <a href="{{ route('admin.teachers.index', ['status' => 'pending']) }}" class="text-xs font-bold text-primary hover:underline">
                    Kelola Semua ({{ $pendingTeachersCount }})
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pendingTeachers as $pt)
                    <div class="p-4 rounded-xl border border-surface-container-high bg-surface-container-low/30 flex flex-col justify-between gap-3">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-primary text-sm">{{ $pt->user->name }}</span>
                                <span class="text-[10px] text-outline">{{ $pt->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant font-medium mt-1">
                                Mapel: <strong>{{ $pt->subject }}</strong> • {{ $pt->origin_location }}
                            </p>
                            <p class="text-xs text-outline mt-0.5">
                                Instansi: {{ $pt->institution_origin ?? 'Mandiri / Relawan' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high">
                            <form action="{{ route('admin.teachers.approve', $pt) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-bold bg-secondary text-white hover:bg-secondary/90 transition-colors">
                                    Setujui
                                </button>
                            </form>
                            <a href="{{ route('admin.teachers.show', $pt) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-surface-container-high text-primary hover:bg-surface-container-low transition-colors">
                                Tinjau
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
