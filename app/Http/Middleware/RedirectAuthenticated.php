<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menggantikan middleware "guest" bawaan: pengguna yang sudah login
 * dikirim ke halaman sesuai peran.
 */
class RedirectAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() !== null) {
            return redirect()->route($request->user()->dashboardRoute());
        }

        return $next($request);
    }
}
