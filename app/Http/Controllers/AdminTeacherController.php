<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTeacherController extends Controller
{
    /**
     * Menampilkan daftar guru relawan dengan filter status verifikasi.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status', 'all')->value();
        $search = $request->string('search')->trim()->value();

        $teachers = Teacher::query()
            ->with(['user', 'schedules'])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('verification_status', $status);
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('subject', 'like', "%{$search}%")
                        ->orWhere('origin_location', 'like', "%{$search}%")
                        ->orWhere('institution_origin', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount  = Teacher::where('verification_status', 'pending')->count();
        $approvedCount = Teacher::where('verification_status', 'approved')->count();
        $rejectedCount = Teacher::where('verification_status', 'rejected')->count();

        return view('admin.teachers.index', compact(
            'teachers',
            'status',
            'search',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Menampilkan detail berkas dan kredensial guru untuk diverifikasi admin.
     */
    public function show(Teacher $teacher): View
    {
        $teacher->load(['user', 'schedules', 'bookings.student', 'reviews']);

        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Admin menyetujui akun guru relawan.
     */
    public function approve(Teacher $teacher): RedirectResponse
    {
        $teacher->update([
            'verification_status' => 'approved',
            'is_verified'         => true,
            'verified_at'         => now(),
            'rejection_reason'    => null,
        ]);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', "Akun guru {$teacher->user->name} berhasil diverifikasi dan kini aktif di halaman pencarian!");
    }

    /**
     * Admin menolak verifikasi berkas guru relawan disertai alasan.
     */
    public function reject(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $teacher->update([
            'verification_status' => 'rejected',
            'is_verified'         => false,
            'rejection_reason'    => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('admin.teachers.index')
            ->with('info', "Verifikasi guru {$teacher->user->name} ditolak dengan alasan yang tercatat.");
    }
}
