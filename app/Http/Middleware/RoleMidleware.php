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
        $roles = explode("|", $statuses[0]);
        // dd($roles);
        $user = $request->user();
        // dd($request->user(),$roles,!$user,!in_array($request->user()->role, $roles),session('account')['role']!='admin' && (!in_array($request->path(), ['user/preferensi/save', 'question-new-user'])));
        if (!$user) {
            return redirect('/Login')->with('fail', ['Akses Ditolak', 'Anda Belum Login, Silahkan login terlebih dahulu!']);
            // abort(403, 'Anda belum login.');
        }
        else if (!in_array($request->user()->role, $roles)) {
            // return redirect('/')->with('fail',['AKSES DITOLAK!','Halaman ini']);
            return redirect('/NotAllowed');
        }
        else if(session('account')['role']!='admin' && (!in_array($request->path(), ['user/preferensi/save', 'question-new-user']))){
            if(session('account')['preferensi_user']==null){
                return redirect('/question-new-user');
            }
        }
        return $next($request);
    }
}
