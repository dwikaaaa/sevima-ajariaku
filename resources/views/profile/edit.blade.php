@extends('layouts.app')

@section('title', 'Edit Profil Pribadi — Ajari Aku')

@section('content')
<div class="flex flex-col w-full pb-16">

    <!-- Header Strip -->
    <section class="w-full bg-surface-container-low px-4 sm:px-8 py-6 border-b border-surface-container-high">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-extrabold text-primary">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $user->isTeacher() ? 'bg-secondary-container text-on-secondary-container' : ($user->isStudent() ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-primary-container text-on-primary') }}">
                            {{ $user->isTeacher() ? 'Guru Relawan' : ($user->isStudent() ? 'Murid / Siswa' : 'Administrator') }}
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $user->email }}</p>
                </div>
            </div>

            <div>
                @php
                    $backUrl = $user->isStudent() ? route('student.dashboard') : ($user->isTeacher() ? route('teacher.dashboard') : route('admin.dashboard'));
                @endphp
                <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-white border border-surface-container-high hover:bg-surface-container-low transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Form Section -->
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-8 py-8">
        
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Kartu 1: Informasi Akun Dasar -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-surface-container-high shadow-sm space-y-6">
                <div class="border-b border-surface-container-high pb-4">
                    <h2 class="text-base font-extrabold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">manage_accounts</span>
                        <span>Informasi Akun Dasar</span>
                    </h2>
                    <p class="text-xs text-on-surface-variant mt-0.5">Kelola nama tampilan dan alamat email login Anda.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                        @error('name')
                            <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-primary mb-1.5">Alamat Email <span class="text-error">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                        @error('email')
                            <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-surface-container-high space-y-4">
                    <div>
                        <h3 class="text-xs font-bold text-primary">Ganti Password (Opsional)</h3>
                        <p class="text-[11px] text-on-surface-variant">Kosongkan jika Anda tidak ingin mengubah password akun Anda saat ini.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Password Baru</label>
                            <input type="password" 
                                   name="password" 
                                   placeholder="Minimal 8 karakter" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('password')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Ulangi Password Baru</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi password baru" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Data Khusus Siswa (Jika Siswa) -->
            @if($user->isStudent())
                @php
                    $sp = $user->studentProfile;
                @endphp
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-surface-container-high shadow-sm space-y-6">
                    <div class="border-b border-surface-container-high pb-4">
                        <h2 class="text-base font-extrabold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">school</span>
                            <span>Profil Belajar Siswa</span>
                        </h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Informasi sekolah dan asal wilayah Anda untuk penyesuaian materi oleh guru relawan.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Asal Wilayah / Daerah Pelosok <span class="text-error">*</span></label>
                            <input type="text" 
                                   name="region_location" 
                                   value="{{ old('region_location', $sp->region_location ?? '') }}" 
                                   placeholder="Contoh: Desa Wae Rebo, Manggarai Barat, NTT" 
                                   required 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('region_location')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Jenjang / Kelas</label>
                            <input type="text" 
                                   name="grade_level" 
                                   value="{{ old('grade_level', $sp->grade_level ?? '') }}" 
                                   placeholder="Contoh: Kelas 6 SD / Kelas 9 SMP / Umum" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('grade_level')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Nama Sekolah / Lembaga Belajar</label>
                            <input type="text" 
                                   name="school_name" 
                                   value="{{ old('school_name', $sp->school_name ?? '') }}" 
                                   placeholder="Contoh: SDN 02 Pelosok" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('school_name')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">No. HP Orang Tua / Wali / Narahubung</label>
                            <input type="text" 
                                   name="guardian_phone" 
                                   value="{{ old('guardian_phone', $sp->guardian_phone ?? '') }}" 
                                   placeholder="Contoh: 081234567890" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('guardian_phone')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-primary mb-1.5">Tujuan & Minat Belajar</label>
                        <textarea name="learning_goals" 
                                  rows="3" 
                                  placeholder="Tuliskan mata pelajaran yang ingin kamu kuasai atau cita-citamu..." 
                                  class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary p-3">{{ old('learning_goals', $sp->learning_goals ?? '') }}</textarea>
                        @error('learning_goals')
                            <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Kartu 3: Data Khusus Guru Relawan (Jika Guru) -->
            @if($user->isTeacher())
                @php
                    $tp = $user->teacherProfile;
                @endphp
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-surface-container-high shadow-sm space-y-6">
                    <div class="border-b border-surface-container-high pb-4">
                        <h2 class="text-base font-extrabold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">psychology</span>
                            <span>Profil Pengajar Relawan</span>
                        </h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Informasi keahlian dan latar belakang mengajar Anda yang akan tampil pada katalog pencarian siswa.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Mata Pelajaran / Bidang Keahlian <span class="text-error">*</span></label>
                            <input type="text" 
                                   name="subject" 
                                   value="{{ old('subject', $tp->subject ?? '') }}" 
                                   placeholder="Contoh: Matematika Dasar & Aljabar" 
                                   required 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('subject')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary mb-1.5">Domisili / Lokasi <span class="text-error">*</span></label>
                            <input type="text" 
                                   name="origin_location" 
                                   value="{{ old('origin_location', $tp->origin_location ?? '') }}" 
                                   placeholder="Contoh: Surabaya, Jawa Timur" 
                                   required 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('origin_location')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-primary mb-1.5">Asal Institusi / Universitas / Komunitas</label>
                            <input type="text" 
                                   name="institution_origin" 
                                   value="{{ old('institution_origin', $tp->institution_origin ?? '') }}" 
                                   placeholder="Contoh: Universitas Airlangga / Komunitas Guru Mengabdi" 
                                   class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary py-2.5 px-3">
                            @error('institution_origin')
                                <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-primary mb-1.5">Biografi & Motivasi Mengajar Relawan</label>
                        <textarea name="bio" 
                                  rows="4" 
                                  placeholder="Ceritakan sedikit tentang latar belakang, pengalaman, dan semangat Anda membantu adik-adik di daerah pelosok..." 
                                  class="w-full text-xs rounded-xl border-surface-container-high focus:border-secondary focus:ring-secondary p-3">{{ old('bio', $tp->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="text-[11px] text-error font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Tombol Aksi Simpan -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ $backUrl }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary/90 shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
