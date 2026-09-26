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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('subject')->index(); // Spesialisasi mata pelajaran (Matematika, Bahasa Inggris, dll.)
            $table->text('bio')->nullable();
            $table->string('origin_location')->index(); // Asal daerah guru untuk filter
            $table->decimal('rating', 3, 2)->default(0.00); // Rata-rata rating (0.00 - 5.00)
            $table->unsignedInteger('total_reviews')->default(0);

            // Verifikasi Berkas Relawan
            $table->string('institution_origin')->nullable(); // Asal kampus/institusi/organisasi relawan
            $table->string('identity_card_path')->nullable(); // Path upload scan KTP / KTM
            $table->string('cv_path')->nullable();            // Path upload CV / Portofolio mengajar
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->index();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable(); // Alasan jika berkas relawan ditolak admin

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
