<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSessionController extends Controller
{
    /**
     * Menampilkan pengawasan menyeluruh terhadap seluruh sesi belajar yang ada di sistem.
     * Mengantisipasi kendala teknis tautan video call atau koordinasi darurat 3T.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status', 'all')->value();
        $search = $request->string('search')->trim()->value();

        $bookings = Booking::query()
            ->with(['student.studentProfile', 'teacher.user', 'schedule', 'review'])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('student', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('teacher.user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('subject', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalApproved = Booking::where('status', 'approved')->count();
        $totalPending  = Booking::where('status', 'pending')->count();
        $totalCompleted= Booking::where('status', 'completed')->count();
        $totalCancelled= Booking::where('status', 'cancelled')->count();

        return view('admin.sessions.index', compact(
            'bookings',
            'status',
            'search',
            'totalApproved',
            'totalPending',
            'totalCompleted',
            'totalCancelled'
        ));
    }

    /**
     * Intervensi darurat: Administrator memperbarui link meeting jika tautan rusak/error.
     */
    public function updateLink(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'link_meeting' => ['required', 'url', 'max:255'],
        ]);

        $booking->update([
            'link_meeting' => $validated['link_meeting'],
        ]);

        return back()->with('success', "Tautan sesi belajar #{$booking->id} berhasil diperbarui oleh admin untuk kelancaran murid.");
    }
}
