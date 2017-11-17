<?php

namespace App\Http\Middleware;

use Closure;

class Login
{
    public  function handle($request,Closure $next){
        if( $request->session()->has('checkLogin') && $request->session()->get('checkLogin')!=null){
        
            return $next($request);
        }
        return redirect('/login');
    }
}