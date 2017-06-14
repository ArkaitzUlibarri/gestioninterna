<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Route;

class CheckRole
{
	private $url;
	private $user;
	private $parameters;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$this->url = $request->fullUrl();
		$this->user = User::find($request->user()->id);
		$this->parameters = $request->route()->parameters();

		//PM
		if(! $this->user->isAdmin() && $this->user->isPM()) {
			if(! $this->validateProyectManager()){
				return redirect('/access'); 
			}
		}
		//User o Tools
		elseif($this->user->isRole('user') || $this->user->isRole('tools')) {
			if(! $this->validateUser()){
				return redirect('/access'); 
			}
		}

		return $next($request);
   
	}

	private function validateUser()
	{
		if(isset($this->parameters['user'])) { //Pantalla Usuarios

			if($this->user->id != intval($this->parameters['user'])){
				return 0; 
			}

			//URl contains categories or groups  
			if (strpos($this->url, 'groups') !== false || strpos($this->url, 'categories') !== false) {
			   return 0;
			}

		}
		elseif (isset($this->parameters['id']) && isset($this->parameters['date'])) { //Pantalla de reportes
			if($this->user->id != intval($this->parameters['id'])){ 
				return 0; 
			}
		}
		else{
			return 0;
		}	
		return 1;	 
	}

	private function validateProyectManager()
	{
		$pmProjects = $this->user->PMProjects();
		$routeName = Route::currentRouteName();
		//dd($routeName);
		//Pantalla de proyectos index
		if (strcmp($routeName, "users.index") === 0) { 
			//dd("a");
			return 1;
		}
		//Pantalla de grupos a usuarios
		elseif(isset($this->parameters['user']) && strpos($this->url, 'groups') !== false) { 
			//dd("b");
			$watchedUser = User::find(intval($this->parameters['user']));
			$groups = $watchedUser->groups;

			if(count($groups) == 0){
				return 1;
			}
			else{
				foreach ($groups as $group) {		
					if(array_key_exists($group->project_id, $pmProjects)){
						return 1;
					}
				}

			}
		}
		//Pantalla de usuarios
		elseif (isset($this->parameters['user'])) {
			//dd("c");
			if($this->user->id == intval($this->parameters['user'])){
				return 1; 
			}
		}	                    			 
		//Pantalla de reportes
		elseif (isset($this->parameters['id']) && isset($this->parameters['date'])) {
			//dd("d"); 	
			//Su reporte
			if($this->user->id == intval($this->parameters['id'])){ 
				return 1; 
			}
			//Reportes de los de su proyecto
			else{
				$watchedUser = User::find(intval($this->parameters['id']));
				$groups = $watchedUser->groups;

				foreach ($groups as $group) {		
					if(array_key_exists($group->project_id, $pmProjects)){
						return 1;
					}
				}
			}
		}
		//Pantalla de proyectos index
		elseif (strcmp($routeName, "projects.index") === 0) { 
			//dd("e");
			return 1;
		}
		//Pantalla de proyectos
		elseif(isset($this->parameters['project']))	{
			//dd("f");
	   		if(array_key_exists(intval($this->parameters['project']), $pmProjects)){
				return 1;
			}
		}
		//dd("fin");
		return 0;
		
	}
}
