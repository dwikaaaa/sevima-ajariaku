<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_level',
        'school_name',
        'region_location',
        'guardian_phone',
        'learning_goals',
    ];

    /**
     * Akun User milik siswa ini (Inverse 1 to 1).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
