<?php

namespace App\Http\Middleware;

use Closure;
class Login
{
    public  function handle($request,Closure $next){
        $val=session('checkLogin');
        if($val){
            return $next($request);
        }
        return redirect('/login');
    }
}