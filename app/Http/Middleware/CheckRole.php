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

		if ($this->user->primaryRole() == 'manager') {
			// Project Manager
			if (! $this->validateProyectManager()){
				return redirect('/access'); 
			}
		}
		elseif ($this->user->primaryRole() == 'user' || $this->user->primaryRole() == 'tools') {
			//User o Tools
			if (! $this->validateUser()){
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
		$projects = $this->user->managerProjects();
		$routeName = Route::currentRouteName();

		//Pantalla de proyectos index
		if (strcmp($routeName, "users.index") === 0) { 
			return 1;
		}

		//Pantalla de grupos a usuarios
		elseif(isset($this->parameters['user']) && strpos($this->url, 'groups') !== false) { 
			$watchedUser = User::find(intval($this->parameters['user']));
			$groups = $watchedUser->groups;

			if(count($groups) == 0){
				return 1;
			}
			else{
				foreach ($groups as $group) {		
					if(array_key_exists($group->project_id, $projects)){
						return 1;
					}
				}
			}
		}

		//Pantalla de usuarios
		elseif (isset($this->parameters['user'])) {
			if($this->user->id == intval($this->parameters['user'])){
				return 1; 
			}
		}	                    			 
		//Pantalla de reportes
		elseif (isset($this->parameters['id']) && isset($this->parameters['date'])) {
			//Su reporte
			if($this->user->id == intval($this->parameters['id'])){ 
				return 1; 
			}
			//Reportes de los de su proyecto
			else{
				$watchedUser = User::find(intval($this->parameters['id']));
				$groups = $watchedUser->groups;

				foreach ($groups as $group) {		
					if(array_key_exists($group->project_id, $projects)){
						return 1;
					}
				}
			}
		}
		//Pantalla de proyectos index
		elseif (strcmp($routeName, "projects.index") === 0) { 
			return 1;
		}
		//Pantalla de proyectos
		elseif(isset($this->parameters['project']))	{
	   		if(array_key_exists(intval($this->parameters['project']), $projects)){
				return 1;
			}
		}

		return 0;
	}
}
