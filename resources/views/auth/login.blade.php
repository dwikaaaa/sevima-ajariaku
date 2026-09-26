@extends('layouts.app')

@section('title', 'Masuk Akun — Ajari Aku')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center p-4 sm:p-8 bg-surface-container-low">
    
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col lg:flex-row border border-surface-container-high">
        
        <!-- Left Column: Form Login -->
        <div class="w-full lg:w-1/2 p-8 sm:p-12 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 32C17 29 21 29 24 31V16C21 14 17 14 12 16V32Z" fill="#E8F5E9"></path>
                            <path d="M36 32C31 29 27 29 24 31V16C27 14 31 14 36 16V32Z" fill="#A3E4D7"></path>
                            <circle cx="24" cy="11" fill="#F4D03F" r="3.2"></circle>
                            <path d="M24 18V29" stroke="#ffffff" stroke-linecap="round" stroke-width="2.5"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl text-primary leading-none block">Ajari Aku</span>
                        <span class="text-[10px] font-bold text-outline uppercase tracking-wider">Jembatan Pendidikan Pelosok</span>
                    </div>
                </div>

                <h1 class="text-2xl font-extrabold text-primary mb-2">Selamat Datang Kembali! 👋</h1>
                <p class="text-xs text-on-surface-variant mb-6">Masuk untuk melanjutkan kegiatan belajar atau mengajar adik-adik pelosok.</p>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-primary mb-1">Alamat Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" 
                               placeholder="nama@email.com" 
                               required autofocus>
                        @error('email')
                            <span class="text-xs text-error mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-primary mb-1">Kata Sandi</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full text-xs rounded-xl border-surface-container-high focus:border-primary focus:ring-primary p-3" 
                               placeholder="••••••••" 
                               required>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded text-primary focus:ring-primary border-surface-container-high">
                            <span class="text-on-surface-variant">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span>Masuk ke Akun</span>
                    </button>
                </form>
            </div>

            <div class="mt-8 pt-6 border-t border-surface-container-high text-center text-xs text-on-surface-variant">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-primary hover:underline">
                    Daftar sebagai Murid atau Guru
                </a>
            </div>
        </div>

        <!-- Right Column: Visual Brand Card (Forest Gradient) -->
        <div class="hidden lg:flex lg:w-1/2 bg-primary-container text-white p-12 flex-col justify-between relative overflow-hidden" 
             style="background: radial-gradient(circle at 75% 25%, #19584b 0%, #123F36 50%, #0d2e27 100%);">
            
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-secondary-container mb-4">
                    <span class="material-symbols-outlined text-[16px]">volunteer_activism</span>
                    Pendidikan Inklusif Merata
                </span>
                <h2 class="text-3xl font-extrabold leading-snug">
                    "Setiap anak Indonesia berhak mendapatkan guru terbaik, di manapun mereka berada."
                </h2>
            </div>

            <!-- Floating Preview Card -->
            <div class="relative z-10 bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-lg space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold">
                        B
                    </div>
                    <div>
                        <span class="font-bold text-sm block">Budi Pratama</span>
                        <span class="text-xs text-surface-dim">Siswa SD • Manggarai, NTT</span>
                    </div>
                </div>
                <p class="text-xs text-surface-dim leading-relaxed">
                    "Dulu saya bingung kalau belajar matematika sendiri. Sekarang berkat Kak Sarah dari Bandung, saya jadi semangat dan paham pecahan!"
                </p>
            </div>

            <div class="relative z-10 text-[11px] text-surface-dim flex items-center justify-between">
                <span>Ajari Aku Platform</span>
                <span>Didukung Teknologi Hemat Kuota</span>
            </div>
        </div>

    </div>

</div>
@endsection
