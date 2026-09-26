<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama guru relawan.
     * Alur Sisi Guru: Kelola slot jadwal ketersediaan, terima booking murid, dan sediakan ruang belajar.
     */
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacherProfile;

        if (! $teacher) {
            abort(404, 'Profil data guru tidak ditemukan.');
        }

        // Slot jadwal yang masih dibuka dan belum lewat
        $availableSchedules = $teacher->schedules()
            ->available()
            ->orderBy('start_time', 'asc')
            ->get();

        // Booking yang masuk dari siswa
        $bookings = Booking::query()
            ->with(['student.studentProfile', 'schedule'])
            ->forTeacher($teacher->id)
            ->latest()
            ->get();

        $pendingBookings  = $bookings->filter(fn ($b) => $b->isPending());
        $approvedBookings = $bookings->filter(fn ($b) => $b->isApproved());
        $completedBookings= $bookings->filter(fn ($b) => $b->isCompleted());

        return view('teacher.dashboard', compact(
            'teacher',
            'availableSchedules',
            'pendingBookings',
            'approvedBookings',
            'completedBookings'
        ));
    }

    /**
     * Guru menambahkan slot jadwal ketersediaan baru untuk mengajar.
     */
    public function storeSchedule(Request $request): RedirectResponse
    {
        $teacher = $request->user()->teacherProfile;

        $validated = $request->validate([
            'start_time' => ['required', 'date', 'after:now'],
            'end_time'   => ['required', 'date', 'after:start_time'],
        ]);

        // Cek bentrok dengan jadwal guru yang sudah ada
        $isConflict = $teacher->schedules()
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function ($sub) use ($validated) {
                          $sub->where('start_time', '<=', $validated['start_time'])
                              ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->exists();

        if ($isConflict) {
            return back()->withErrors([
                'start_time' => 'Rentang waktu ini bertabrakan dengan jadwal mengajar Anda yang lain.',
            ])->withInput();
        }

        $teacher->schedules()->create([
            'start_time'   => $validated['start_time'],
            'end_time'     => $validated['end_time'],
            'is_available' => true,
        ]);

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Slot ketersediaan mengajar berhasil ditambahkan!');
    }

    /**
     * Menghapus slot jadwal ketersediaan yang belum dibooking.
     */
    public function destroySchedule(Request $request, Schedule $schedule): RedirectResponse
    {
        $teacher = $request->user()->teacherProfile;

        if ($schedule->teacher_id !== $teacher->id) {
            abort(403, 'Akses ditolak.');
        }

        if (! $schedule->is_available) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah dibooking oleh murid.');
        }

        $schedule->delete();

        return redirect()
            ->route('teacher.dashboard')
            ->with('info', 'Slot jadwal berhasil dihapus.');
    }
}
