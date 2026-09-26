<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed akun admin default aplikasi.
     *
     * Idempotent: dijalankan berulang tidak menduplikasi data,
     * akun yang sudah ada akan diperbarui (password & role di-reset).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@sevima.test')],
            [
                'name' => env('ADMIN_NAME', 'Administrator Sevima'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
