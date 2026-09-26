<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Profil guru jika user memiliki role 'guru' (1 to 1).
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    /**
     * Profil siswa jika user memiliki role 'siswa' (1 to 1).
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * Riwayat pemesanan sesi belajar yang dilakukan oleh user ini sebagai siswa (1 to many).
     */
    public function studentBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    /**
     * Ulasan yang telah diberikan oleh user ini sebagai siswa (1 to many).
     */
    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    /**
     * Helper cek apakah user adalah Guru/Relawan.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Helper cek apakah user adalah Siswa.
     */
    public function isStudent(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Helper cek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
