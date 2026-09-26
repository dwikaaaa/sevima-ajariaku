<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Memproses pemesanan sesi belajar oleh siswa.
     * Menggunakan Database Transaction dan Row-Level Locking (lockForUpdate)
     * untuk mencegah race condition (double booking pada slot yang sama).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        $student = $request->user();

        try {
            $booking = DB::transaction(function () use ($validated, $student) {
                // Kunci baris jadwal ketersediaan untuk menghindari race condition
                $schedule = Schedule::where('id', $validated['schedule_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Validasi apakah slot masih tersedia dan waktunya belum lewat
                if (! $schedule->is_available || $schedule->start_time <= now()) {
                    throw ValidationException::withMessages([
                        'schedule_id' => 'Maaf, slot jadwal ini baru saja diambil orang lain atau sudah lewat.',
                    ]);
                }

                // Pastikan tidak ada sesi aktif (pending/approved/completed) pada slot ini
                $hasActiveBooking = Booking::where('schedule_id', $schedule->id)
                    ->whereIn('status', ['pending', 'approved', 'completed'])
                    ->exists();

                if ($hasActiveBooking) {
                    throw ValidationException::withMessages([
                        'schedule_id' => 'Maaf, slot jadwal ini sedang memiliki sesi belajar aktif.',
                    ]);
                }

                // Buat record booking
                $newBooking = Booking::create([
                    'student_id'   => $student->id,
                    'teacher_id'   => $schedule->teacher_id,
                    'schedule_id'  => $schedule->id,
                    'status'       => 'pending',
                    'notes'        => $validated['notes'] ?? null,
                ]);

                // Kunci slot jadwal agar tidak dapat dipilih lagi oleh murid lain
                $schedule->update([
                    'is_available' => false,
                ]);

                return $newBooking;
            });

            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Sesi belajar berhasil dipesan! Menunggu persetujuan guru.');

        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                throw $e;
            }

            return back()->with('error', 'Terjadi kesalahan sistem saat memesan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Guru menyetujui pemesanan sesi belajar dan menyematkan link ruang pertemuan.
     */
    public function approve(Request $request, Booking $booking): RedirectResponse
    {
        $teacher = $request->user()->teacherProfile;

        // Pastikan hanya guru pemilik sesi yang dapat menyetujui
        if (! $teacher || $booking->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengelola sesi belajar ini.');
        }

        $validated = $request->validate([
            'link_meeting' => ['nullable', 'url', 'max:255'],
        ]);

        // Jika guru tidak menyertakan link, generate Jitsi Meet otomatis instan
        $linkMeeting = $validated['link_meeting'] ?? Booking::createJitsiMeetingUrl($booking->id);

        $booking->update([
            'status'       => 'approved',
            'link_meeting' => $linkMeeting,
        ]);

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Pemesanan sesi belajar disetujui. Tautan kelas telah aktif!');
    }

    /**
     * Guru menolak/membatalkan pemesanan sesi belajar.
     * Mengembalikan ketersediaan slot jadwal agar dapat dipilih murid lain.
     */
    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        $teacher = $request->user()->teacherProfile;

        if (! $teacher || $booking->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki hak untuk membatalkan sesi belajar ini.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            // Ubah status booking menjadi cancelled
            $booking->update([
                'status'              => 'cancelled',
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            // Buka kembali ketersediaan jadwal jika waktu mulainya belum lewat
            if ($booking->schedule && $booking->schedule->start_time > now()) {
                $booking->schedule->update([
                    'is_available' => true,
                ]);
            }
        });

        return redirect()
            ->route('teacher.dashboard')
            ->with('info', 'Sesi belajar dibatalkan dan slot jadwal telah dibuka kembali.');
    }

    /**
     * Guru menandai sesi belajar telah selesai dan mengisi catatan perkembangan belajar murid.
     */
    public function complete(Request $request, Booking $booking): RedirectResponse
    {
        $teacher = $request->user()->teacherProfile;

        if (! $teacher || $booking->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki hak untuk menyelesaikan sesi belajar ini.');
        }

        // Validasi waktu: Guru hanya bisa menandai sesi selesai setelah waktu jadwal berakhir
        if (! $booking->isScheduleEnded()) {
            $endTimeFormatted = $booking->schedule?->end_time ? $booking->schedule->end_time->translatedFormat('H:i') : '-';
            return back()->with('error', "Sesi pembelajaran belum dapat ditandai selesai. Anda baru dapat menyelesaikannya setelah jadwal berakhir pada pukul {$endTimeFormatted} WIB.");
        }

        $validated = $request->validate([
            'summary_notes' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'summary_notes.required' => 'Catatan rangkuman hasil belajar murid wajib diisi.',
            'summary_notes.min'      => 'Catatan rangkuman minimal berisi 3 karakter.',
        ]);

        $booking->update([
            'status'             => 'completed',
            'summary_notes'      => $validated['summary_notes'],
            'student_attendance' => $request->boolean('student_attendance', true),
            'completed_at'       => now(),
        ]);

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Sesi belajar telah diselesaikan. Catatan belajar berhasil tersimpan!');
    }

    /**
     * Siswa memberikan ulasan (rating & feedback) setelah sesi selesai.
     */
    public function review(Request $request, Booking $booking): RedirectResponse
    {
        $student = $request->user();

        // Validasi kepemilikan booking dan kelayakan review
        if ($booking->student_id !== $student->id || ! $booking->canBeReviewed()) {
            abort(403, 'Sesi ini belum selesai atau sudah pernah diulas.');
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($booking, $student, $validated) {
            Review::create([
                'booking_id' => $booking->id,
                'student_id' => $student->id,
                'teacher_id' => $booking->teacher_id,
                'rating'     => $validated['rating'],
                'comment'    => $validated['comment'] ?? null,
            ]);

            // Hitung ulang rata-rata rating guru
            $booking->teacher->recalculateRating();
        });

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Terima kasih! Ulasan Anda telah dikirimkan untuk guru relawan.');
    }
}
