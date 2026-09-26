<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'bio',
        'origin_location',
        'rating',
        'total_reviews',
        'institution_origin',
        'identity_card_path',
        'cv_path',
        'verification_status',
        'is_verified',
        'verified_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'total_reviews' => 'integer',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Akun User pemilik profil guru ini (Inverse 1 to 1).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Daftar slot ketersediaan mengajar milik guru ini (1 to many).
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    /**
     * Daftar booking sesi belajar yang diarahkan ke guru ini (1 to many).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'teacher_id');
    }

    /**
     * Daftar ulasan/rating dari para siswa yang pernah belajar dengan guru ini (1 to many).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'teacher_id');
    }

    /**
     * Hitung ulang dan perbarui rata-rata rating serta total review guru ini.
     */
    public function recalculateRating(): void
    {
        $avg = $this->reviews()->avg('rating') ?? 0.00;
        $count = $this->reviews()->count();

        $this->update([
            'rating' => round($avg, 2),
            'total_reviews' => $count,
        ]);
    }

    /**
     * Scope untuk pencarian guru berdasarkan nama, mata pelajaran, atau asal daerah (Discovery alur siswa).
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('subject', 'like', "%{$search}%")
                     ->orWhere('origin_location', 'like', "%{$search}%")
                     ->orWhereHas('user', function ($userQ) use ($search) {
                         $userQ->where('name', 'like', "%{$search}%");
                     });
            });
        });

        $query->when($filters['subject'] ?? null, function ($q, $subject) {
            $q->where('subject', 'like', "%{$subject}%");
        });

        $query->when($filters['origin_location'] ?? null, function ($q, $location) {
            $q->where('origin_location', 'like', "%{$location}%");
        });
    }

    /**
     * Scope hanya guru yang sudah diverifikasi berkasnya oleh admin.
     */
    public function scopeVerified(Builder $query): void
    {
        $query->where('verification_status', 'approved')
              ->where('is_verified', true);
    }
}
