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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->cascadeOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('is_available')->default(true)->index(); // true jika belum dibooking
            $table->timestamps();

            // Mencegah duplikasi slot waktu yang persis sama pada guru yang sama
            $table->unique(['teacher_id', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
