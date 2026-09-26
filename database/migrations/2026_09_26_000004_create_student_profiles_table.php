<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('grade_level'); // Jenjang (contoh: "SD Kelas 5", "SMP Kelas 8", "SMA Kelas 11")
            $table->string('school_name'); // Nama sekolah asal di daerah terpencil
            $table->string('region_location'); // Kabupaten / Desa / Daerah 3T asal siswa
            $table->string('guardian_phone')->nullable(); // Kontak WhatsApp siswa / wali / guru pamong
            $table->text('learning_goals')->nullable(); // Minat atau tujuan belajar siswa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
