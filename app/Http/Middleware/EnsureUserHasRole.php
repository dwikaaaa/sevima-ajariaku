<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }


        // Cek apakah role user saat ini ada dalam daftar role yang diperbolehkan
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
