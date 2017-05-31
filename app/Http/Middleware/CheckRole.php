<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->isAdmin() && ! $request->user()->isPM()){
            if($request->user()->id != intval($request->route('id'))){
                //throw new \Exception("Error Processing Request, You are not admin"); 
                return redirect('/access'); 
           }    
        }
        
        return $next($request);
    }
}
