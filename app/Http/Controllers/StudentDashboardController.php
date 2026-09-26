<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk murid/siswa.
     * Alur Sisi Murid: Melihat sesi belajar aktif, link video conference, dan catatan dari guru.
     */
    public function index(Request $request): View
    {
        $student = $request->user();

        // Ambil semua booking milik siswa beserta relasi guru dan jadwal
        $bookings = Booking::query()
            ->with(['teacher.user', 'schedule', 'review'])
            ->forStudent($student->id)
            ->latest()
            ->get();

        // Pengelompokan sesi belajar untuk kemudahan navigasi siswa di daerah terpencil
        $upcomingSessions = $bookings->filter(fn ($b) => $b->isApproved() && $b->schedule && $b->schedule->start_time >= now()->subHours(2));
        $pendingSessions  = $bookings->filter(fn ($b) => $b->isPending());
        $completedSessions= $bookings->filter(fn ($b) => $b->isCompleted());
        $cancelledSessions= $bookings->filter(fn ($b) => $b->isCancelled());

        return view('student.dashboard', compact(
            'student',
            'upcomingSessions',
            'pendingSessions',
            'completedSessions',
            'cancelledSessions'
        ));
    }
}
