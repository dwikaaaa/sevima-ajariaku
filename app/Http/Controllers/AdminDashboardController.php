<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama administrator dengan indikator dampak (Impact Metrics).
     * Sangat berguna untuk presentasi dan pengawasan operasional sesi 3T.
     */
    public function index(Request $request): View
    {
        // 1. Impact Metrics (Metrik Dampak)
        $totalStudents = User::where('role', 'siswa')->count();
        $totalTeachers = Teacher::where('verification_status', 'approved')->count();
        $pendingTeachersCount = Teacher::where('verification_status', 'pending')->count();
        $totalCompletedSessions = Booking::where('status', 'completed')->count();
        
        // Perkiraan jam belajar efektif (rata-rata 1 sesi = 1 jam)
        $totalLearningHours = $totalCompletedSessions * 1.5;

        // Rata-rata kepuasan / rating sistem
        $avgRating = Teacher::where('total_reviews', '>', 0)->avg('rating') ?: 5.0;
        $satisfactionRate = round(($avgRating / 5.0) * 100, 1);

        // 2. Monitoring Sesi Berlangsung / Aktif (Realtime 3T Radar)
        $activeSessions = Booking::query()
            ->with(['student.studentProfile', 'teacher.user', 'schedule'])
            ->whereIn('status', ['approved', 'pending'])
            ->latest()
            ->take(10)
            ->get();

        // 3. Guru Baru yang Menunggu Verifikasi Segera
        $pendingTeachers = Teacher::query()
            ->with('user')
            ->where('verification_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'pendingTeachersCount',
            'totalCompletedSessions',
            'totalLearningHours',
            'satisfactionRate',
            'avgRating',
            'activeSessions',
            'pendingTeachers'
        ));
    }
}
