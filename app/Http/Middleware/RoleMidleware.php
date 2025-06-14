<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$statuses): Response
    {
        // dd($request->user());
        // $allSattus = explode("|", $statuses[0]);
        // dd(session('account'));
        // dd($allSattus);
        // dd(session('account'));
        // dd(!session('account') || !in_array($request->user()->status, $allSattus),$request->user());
        if (!$request->user() || !in_array($request->user()->status, $allSattus)) {
            abort(403, 'Anda Belum Login atau Halaman ini tidak bisa diakses oleh Role anda saat ini.');
        }

        return $next($request);
    }
}
