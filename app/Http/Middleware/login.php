<?php


namespace App\Http\Middleware;
use Closure;
class login
{

    public  function handle($request,Closure $next){
      $val=session('login','0');
        if($val){
            return $next($request);
        }
        return redirect('index');



    }

}