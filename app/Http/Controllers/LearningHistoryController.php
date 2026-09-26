<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LearningHistoryController extends Controller
{
    /**
     * Menampilkan riwayat/histori pembelajaran untuk siswa maupun guru relawan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $status = $request->query('status', 'all');

        $query = Booking::query()->with(['schedule', 'review']);

        if ($user->isStudent()) {
            $query->with('teacher.user')->forStudent($user->id);
        } elseif ($user->isTeacher()) {
            $teacher = $user->teacherProfile;
            if ($teacher) {
                $query->with('student.studentProfile')->forTeacher($teacher->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->isAdmin()) {
            $query->with(['student.studentProfile', 'teacher.user']);
        }

        // Hitung ringkasan statistik
        $allBookings = (clone $query)->get();
        $completedBookings = $allBookings->filter(fn ($b) => $b->isCompleted());
        
        // Menghitung estimasi total durasi sesi selesai dalam jam
        $totalHours = 0;
        foreach ($completedBookings as $cb) {
            if ($cb->schedule && $cb->schedule->start_time && $cb->schedule->end_time) {
                $diffInMinutes = $cb->schedule->start_time->diffInMinutes($cb->schedule->end_time);
                $totalHours += $diffInMinutes / 60;
            } else {
                $totalHours += 1; // Default fallback 1 jam per sesi
            }
        }
        $totalHours = round($totalHours, 1);

        $attendedCount = $completedBookings->where('student_attendance', true)->count();
        $attendanceRate = $completedBookings->count() > 0 
            ? round(($attendedCount / $completedBookings->count()) * 100) 
            : 100;

        $stats = [
            'total'          => $allBookings->count(),
            'completed'      => $completedBookings->count(),
            'pending'        => $allBookings->filter(fn ($b) => $b->isPending())->count(),
            'approved'       => $allBookings->filter(fn ($b) => $b->isApproved())->count(),
            'cancelled'      => $allBookings->filter(fn ($b) => $b->isCancelled())->count(),
            'total_hours'    => $totalHours,
            'attended_count' => $attendedCount,
            'attendance_rate'=> $attendanceRate,
        ];

        // Filter berdasarkan status jika diminta
        if (in_array($status, ['completed', 'approved', 'pending', 'cancelled'])) {
            $query->where('status', $status);
        }

        // Urutkan berdasarkan waktu sesi terbaru
        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('history.index', compact('user', 'bookings', 'stats', 'status'));
    }
}
