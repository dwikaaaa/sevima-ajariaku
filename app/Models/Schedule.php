<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Profil guru pemilik slot jadwal mengajar ini (Inverse 1 to many).
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Transaksi booking terkini yang mengunci jadwal ini.
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'schedule_id')->latestOfMany();
    }

    /**
     * Seluruh riwayat transaksi booking pada jadwal ini.
     */
    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    /**
     * Scope untuk slot jadwal yang masih tersedia untuk dibooking siswa.
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_available', true)
              ->where('start_time', '>', now());
    }

    /**
     * Scope jadwal yang akan datang (mendatang).
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('start_time', '>=', now())
              ->orderBy('start_time', 'asc');
    }
}
