<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherSearchController extends Controller
{
    /**
     * Menampilkan katalog pencarian guru relawan dengan filter mata pelajaran dan lokasi.
     * Alur Discovery Siswa.
     */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if (auth()->check() && auth()->user()->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        $search = $request->string('search')->trim()->value();
        $subject = $request->string('subject')->trim()->value();
        $location = $request->string('origin_location')->trim()->value();

        // Query guru yang terverifikasi dan filter
        $teachers = Teacher::query()
            ->with(['user', 'schedules' => fn ($q) => $q->available()])
            ->verified()
            ->filter([
                'search'          => $search,
                'subject'         => $subject,
                'origin_location' => $location,
            ])
            ->withCount(['reviews', 'schedules as available_schedules_count' => fn ($q) => $q->available()])
            ->latest('rating')
            ->paginate(12)
            ->withQueryString();

        // Opsi daftar mata pelajaran unik untuk dropdown filter
        $availableSubjects = Teacher::query()
            ->verified()
            ->distinct()
            ->pluck('subject')
            ->sort();

        // Opsi daerah asal guru unik untuk dropdown filter
        $availableLocations = Teacher::query()
            ->verified()
            ->distinct()
            ->pluck('origin_location')
            ->sort();

        return view('teachers.index', compact(
            'teachers',
            'availableSubjects',
            'availableLocations',
            'search',
            'subject',
            'location'
        ));
    }

    /**
     * Menampilkan detail profil guru relawan beserta daftar jadwal ketersediaan mengajarnya.
     */
    public function show(Teacher $teacher): View|\Illuminate\Http\RedirectResponse
    {
        if (auth()->check() && auth()->user()->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        // Eager load relasi penting
        $teacher->load([
            'user',
            'schedules' => fn ($q) => $q->available()->orderBy('start_time', 'asc'),
            'reviews.student',
        ]);

        return view('teachers.show', compact('teacher'));
    }
}
