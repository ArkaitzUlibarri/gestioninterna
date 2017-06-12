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
        $loggedUser = $request->user()->id;//Usuario logueado
        $parameters = $request->route()->parameters();//Parametros
        $url = $request->fullUrl();//Url

        //User
        /*
        if (! $request->user()->isAdmin() && ! $request->user()->isPM()){
            if(isset($parameters['id'])){
                if($loggedUser != intval($parameters['id'])){
                    //throw new \Exception("Error Processing Request, You are not admin"); 
                    return redirect('/access'); 
                } 
            }
            else{
                //throw new \Exception("Error Processing Request, You are not admin"); 
                return redirect('/access'); 
            }
        }

        //PM
        if (! $request->user()->isAdmin() && $request->user()->isPM()){
            if ($request->isMethod('post')) {       
                if($loggedUser != intval($request->route('id'))){
                    throw new \Exception("Error Processing Request, You are not admin"); 
                    //return redirect('/access'); 
                } 
            } 
        }
        */
        return $next($request);
    }
}
