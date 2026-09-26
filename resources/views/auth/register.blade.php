@extends('layouts.app')

@section('title', 'Daftar Akun Baru — Ajari Aku')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center p-4 sm:p-8 bg-surface-container-low">
    
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden border border-surface-container-high p-8 sm:p-12">
        
        <div class="text-center max-w-lg mx-auto mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-container text-white rounded-2xl shadow-sm mb-3">
                <span class="material-symbols-outlined text-[28px]">person_add</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-primary">Bergabung dengan Ajari Aku</h1>
            <p class="text-xs text-on-surface-variant mt-1">
                Pilih peran Anda untuk memulai petualangan belajar atau mengajar adik-adik pelosok.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-error-container/50 border border-error text-error text-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
            @csrf

            <!-- Role Selector Card -->
            <div>
                <label class="block text-xs font-bold text-primary mb-2 text-center uppercase tracking-wider">
                    Pilih Peran Akun Anda
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl mx-auto">
                    <!-- Siswa Option -->
                    <label class="relative flex items-center p-4 rounded-2xl border-2 border-surface-container-high cursor-pointer transition-all hover:bg-surface-container-low has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/20 has-[:checked]:ring-2 has-[:checked]:ring-secondary">
                        <input type="radio" name="role" value="siswa" class="sr-only" checked onchange="toggleRoleFields('siswa')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-secondary-container text-on-secondary-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-[24px]">school</span>
                            </div>
                            <div>
                                <span class="text-sm font-extrabold text-primary block">Saya adalah Siswa</span>
                                <span class="text-[11px] text-on-surface-variant">Ingin belajar dari guru relawan</span>
                            </div>
                        </div>
                    </label>

                    <!-- Guru Relawan Option -->
                    <label class="relative flex items-center p-4 rounded-2xl border-2 border-surface-container-high cursor-pointer transition-all hover:bg-surface-container-low has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/20 has-[:checked]:ring-2 has-[:checked]:ring-secondary">
                        <input type="radio" name="role" value="guru" class="sr-only" onchange="toggleRoleFields('guru')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[24px]">volunteer_activism</span>
                            </div>
                            <div>
                                <span class="text-sm font-extrabold text-primary block">Guru / Relawan</span>
                                <span class="text-[11px] text-on-surface-variant">Ingin mengajar adik pelosok</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Basic Auth Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-surface-container-high">
                <div>
                    <label for="name" class="block text-xs font-bold text-primary mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Contoh: Budi Pratama" required>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-primary mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="nama@email.com" required>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-primary mb-1">Kata Sandi (Min. 8 karakter)</label>
                    <input type="password" name="password" id="password" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="••••••••" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-primary mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Conditional Section: Siswa Fields -->
            <div id="studentFields" class="space-y-4 pt-4 border-t border-surface-container-high">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">school</span> Informasi Profil Siswa
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">Jenjang Kelas</label>
                        <select name="grade_level" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3">
                            <option value="SD Kelas 4-6">SD (Kelas 4 - 6)</option>
                            <option value="SMP Kelas 7-9">SMP (Kelas 7 - 9)</option>
                            <option value="SMA Kelas 10-12">SMA / SMK (Kelas 10 - 12)</option>
                            <option value="Umum / Paket">Umum / Kejar Paket</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ old('school_name') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Contoh: SDN 02 Wae Rebo">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">No. WhatsApp Wali/Murid</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <!-- Conditional Section: Guru Fields (Hidden by default) -->
            <div id="teacherFields" class="space-y-4 pt-4 border-t border-surface-container-high hidden">
                <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">menu_book</span> Spesialisasi Guru Relawan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">Mata Pelajaran Spesialisasi</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Contoh: Matematika SD, Bahasa Inggris">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">Asal Kampus / Instansi</label>
                        <input type="text" name="institution_origin" value="{{ old('institution_origin') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Contoh: Universitas Indonesia / Praktisi">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-primary mb-1">Bio Singkat Relawan</label>
                    <textarea name="bio" rows="2" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Ceritakan pengalaman dan motivasi Anda mengajar adik-adik pelosok..."></textarea>
                </div>
            </div>

            <!-- Lokasi Domisili (Shared by both roles) -->
            <div class="pt-2">
                <label for="region_location" class="block text-xs font-bold text-primary mb-1">
                    Asal Daerah / Kota / Kabupaten
                </label>
                <input type="text" name="region_location" id="region_location" value="{{ old('region_location') }}" class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" placeholder="Contoh: Manggarai Barat, NTT (untuk siswa) atau Bandung (untuk guru)" required>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl text-xs font-extrabold bg-primary text-white hover:bg-primary-container shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                <span>Daftar Akun Sekarang</span>
            </button>

            <p class="text-center text-xs text-on-surface-variant">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Masuk di sini</a>
            </p>
        </form>

    </div>

</div>

@push('scripts')
<script>
    function toggleRoleFields(role) {
        const studentFields = document.getElementById('studentFields');
        const teacherFields = document.getElementById('teacherFields');
        if (role === 'siswa') {
            studentFields.classList.remove('hidden');
            teacherFields.classList.add('hidden');
        } else {
            studentFields.classList.add('hidden');
            teacherFields.classList.remove('hidden');
        }
    }
</script>
@endpush
@endsection
