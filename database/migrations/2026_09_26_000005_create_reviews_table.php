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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // 1 booking sesi belajar hanya dapat direview 1 kali
            $table->foreignId('booking_id')
                  ->unique()
                  ->constrained('bookings')
                  ->cascadeOnDelete();

            // Murid pemberi review
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Guru penerima review
            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating'); // 1 sampai 5 bintang
            $table->text('comment')->nullable();   // Ulasan / kesan murid

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
