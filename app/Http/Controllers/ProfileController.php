<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir edit profil pribadi untuk siswa dan guru.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['studentProfile', 'teacherProfile']);

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui data profil pribadi pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($user->isStudent()) {
            $rules['grade_level']     = ['nullable', 'string', 'max:100'];
            $rules['school_name']     = ['nullable', 'string', 'max:255'];
            $rules['region_location'] = ['required', 'string', 'max:255'];
            $rules['guardian_phone']  = ['nullable', 'string', 'max:20'];
            $rules['learning_goals']  = ['nullable', 'string', 'max:1000'];
        } elseif ($user->isTeacher()) {
            $rules['subject']            = ['required', 'string', 'max:255'];
            $rules['origin_location']    = ['required', 'string', 'max:255'];
            $rules['institution_origin'] = ['nullable', 'string', 'max:255'];
            $rules['bio']                = ['nullable', 'string', 'max:1000'];
            $rules['cv_path']            = ['nullable', 'url', 'max:500'];
        }

        $validated = $request->validate($rules);

        // Update data akun User
        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Update data profil spesifik role
        if ($user->isStudent()) {
            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'grade_level'     => $validated['grade_level'] ?? 'Umum',
                    'school_name'     => $validated['school_name'] ?? 'Daerah Terpencil',
                    'region_location' => $validated['region_location'],
                    'guardian_phone'  => $validated['guardian_phone'] ?? null,
                    'learning_goals'  => $validated['learning_goals'] ?? null,
                ]
            );
        } elseif ($user->isTeacher()) {
            Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'subject'            => $validated['subject'],
                    'origin_location'    => $validated['origin_location'],
                    'institution_origin' => $validated['institution_origin'] ?? null,
                    'bio'                => $validated['bio'] ?? null,
                    'cv_path'            => $validated['cv_path'] ?? null,
                ]
            );
        }

        return redirect()->route('profile.edit')->with('success', 'Profil pribadi Anda berhasil diperbarui!');
    }
}
