<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna terdaftar (siswa, guru, admin) dengan fitur filter peran dan pencarian.
     * Menggunakan kolom standar tabel users dari migrasi resmi.
     */
    public function index(Request $request): View
    {
        $role   = $request->string('role', 'all')->value();
        $search = $request->string('search')->trim()->value();

        $users = User::query()
            ->with(['studentProfile', 'teacherProfile'])
            ->when($role !== 'all', function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalAll      = User::count();
        $totalStudents = User::where('role', 'siswa')->count();
        $totalTeachers = User::where('role', 'guru')->count();
        $totalAdmins   = User::where('role', 'admin')->count();

        return view('admin.users.index', compact(
            'users',
            'role',
            'search',
            'totalAll',
            'totalStudents',
            'totalTeachers',
            'totalAdmins'
        ));
    }

    /**
     * Menghapus akun pengguna dari sistem (untuk akun spam atau laporan pelanggaran).
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('info', "Akun pengguna {$userName} telah berhasil dihapus dari sistem.");
    }
}
