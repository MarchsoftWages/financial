<?php

namespace App\Http\Middleware;

use Closure;
class Login
{
    public  function handle($request,Closure $next){
        $val=session('checkLogin','0');
        if($val){
            return $next($request);
        }
        return redirect('/');
    }

}