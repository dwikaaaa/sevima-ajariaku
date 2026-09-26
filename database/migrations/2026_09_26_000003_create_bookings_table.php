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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Murid pemesan (merujuk ke tabel users dengan role 'siswa')
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Guru relawan yang dituju
            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->cascadeOnDelete();

            // Slot jadwal ketersediaan guru (1 jadwal hanya dapat dibooking 1 kali)
            $table->foreignId('schedule_id')
                  ->unique()
                  ->constrained('schedules')
                  ->cascadeOnDelete();

            // Status transaksi sesi belajar
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])
                  ->default('pending')
                  ->index();

            // Tautan video conference (misal: Jitsi Meet / Google Meet) yang diisi oleh guru
            $table->string('link_meeting')->nullable();

            // Catatan atau topik belajar / kesulitan yang ingin dipelajari siswa
            $table->text('notes')->nullable();

            // Summary notes & Evaluasi hasil belajar dari Guru setelah sesi selesai
            $table->text('summary_notes')->nullable(); // Rangkuman materi, catatan perkembangan siswa, PR
            $table->boolean('student_attendance')->default(true); // Kehadiran siswa
            $table->timestamp('completed_at')->nullable();

            // Alasan pembatalan jika sesi dibatalkan
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
