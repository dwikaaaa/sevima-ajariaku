<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna dan mengarahkan sesuai role.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                session()->forget('url.intended');
                return redirect()->route('admin.dashboard');
            }

            if ($user->isTeacher()) {
                session()->forget('url.intended');
                return redirect()->route('teacher.dashboard');
            }

            if ($user->isStudent()) {
                return redirect()->intended(route('student.dashboard'));
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form registrasi.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi akun baru (siswa atau guru relawan).
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:siswa,guru'],

            // Kolom spesifik siswa
            'grade_level'     => ['required_if:role,siswa', 'nullable', 'string', 'max:100'],
            'school_name'     => ['required_if:role,siswa', 'nullable', 'string', 'max:255'],
            'region_location' => ['required', 'string', 'max:255'],
            'guardian_phone'  => ['nullable', 'string', 'max:20'],

            // Kolom spesifik guru relawan
            'subject'            => ['required_if:role,guru', 'nullable', 'string', 'max:255'],
            'institution_origin' => ['nullable', 'string', 'max:255'],
            'bio'                => ['nullable', 'string', 'max:1000'],
            'cv_path'            => ['required_if:role,guru', 'nullable', 'url', 'max:500'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => $validated['role'],
            ]);

            if ($validated['role'] === 'siswa') {
                StudentProfile::create([
                    'user_id'         => $user->id,
                    'grade_level'     => $validated['grade_level'] ?? 'Umum',
                    'school_name'     => $validated['school_name'] ?? 'Daerah Terpencil',
                    'region_location' => $validated['region_location'],
                    'guardian_phone'  => $validated['guardian_phone'] ?? null,
                ]);
            } elseif ($validated['role'] === 'guru') {
                Teacher::create([
                    'user_id'            => $user->id,
                    'subject'            => $validated['subject'],
                    'origin_location'    => $validated['region_location'],
                    'institution_origin' => $validated['institution_origin'] ?? null,
                    'bio'                => $validated['bio'] ?? null,
                    'cv_path'            => $validated['cv_path'] ?? null,
                    'rating'             => 0.00,
                    'total_reviews'      => 0,
                    'is_verified'        => false, // Menunggu verifikasi admin
                    'verification_status'=> 'pending',
                ]);
            }

            Auth::login($user);
        });

        $user = Auth::user();

        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard')->with('success', 'Pendaftaran guru relawan berhasil! Lengkapi profil dan jadwal mengajar Anda.');
        }

        return redirect()->route('student.dashboard')->with('success', 'Selamat datang! Temukan guru relawan dan mulai belajar sekarang.');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Anda telah berhasil keluar dari akun.');
    }
}
