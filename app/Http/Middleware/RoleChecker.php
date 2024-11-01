<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$roleCheck)
    {
        $userRoles = Auth::user()->roles->toArray();

        foreach ($userRoles as $r) {
            if($r == $roleCheck) return $next($request);
                // return $next($request);
        }

        abort(403, "Anda tidak terdaftar dalam Role* untuk mengakses fungsi ini !!!");

        // $role = $user->AppRoles->where('app', $app)->first();

        // if(!$role){
        // abort(403, "Anda tidak terdaftar dalam Role* untuk mengakses fungsi ini !!!");
        // }
        // if(in_array($role->role, $roles) == false){
        //     abort(403, "Anda tidak terdaftar dalam Role untuk mengakses fungsi ini !!!");
        // }

        // return $next($request);
    }
}
