<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'schedule_id',
        'status',
        'link_meeting',
        'notes',
        'summary_notes',
        'student_attendance',
        'completed_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'student_attendance' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Siswa pemesan sesi belajar (Inverse 1 to many dari User).
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Profil guru yang dituju (Inverse 1 to many dari Teacher).
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Slot waktu mengajar yang dipesan (Inverse 1 to 1 dari Schedule).
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Ulasan dari siswa atas sesi belajar ini (1 to 1).
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    // Helper status checking
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Cek apakah waktu sesi mengajar telah melewati jadwal berakhir (end_time).
     */
    public function isScheduleEnded(): bool
    {
        if (! $this->schedule || ! $this->schedule->end_time) {
            return true;
        }

        return now()->greaterThanOrEqualTo($this->schedule->end_time);
    }

    /**
     * Cek apakah sesi ini sudah selesai dan siap diberi ulasan oleh murid.
     */
    public function canBeReviewed(): bool
    {
        return $this->isCompleted() && !$this->review()->exists();
    }

    /**
     * Helper otomatis untuk men-generate link Jitsi Meet instan tanpa biaya,
     * sangat optimal untuk akses cepat siswa di daerah terpencil.
     */
    public static function createJitsiMeetingUrl(int|string $bookingId): string
    {
        $randomCode = Str::random(10);
        return "https://meet.jit.si/sevima-edukasi-sesi-{$bookingId}-{$randomCode}";
    }

    // Scopes
    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', 'approved');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', 'completed');
    }

    public function scopeForStudent(Builder $query, int $studentId): void
    {
        $query->where('student_id', $studentId);
    }

    public function scopeForTeacher(Builder $query, int $teacherId): void
    {
        $query->where('teacher_id', $teacherId);
    }
}
