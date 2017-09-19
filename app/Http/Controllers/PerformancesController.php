<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRepository;
use App\User;
use App\Project;
use Illuminate\Support\Facades\Auth;

class PerformancesController extends Controller
{
	protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        //$this->middleware('checkrole');
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if(Auth::user()->primaryRole() == 'admin'){
            $projects = Project::all();
            $projects = array_pluck($projects, 'name', 'id');
        }
        elseif (Auth::user()->primaryRole() == 'manager'){
            $projects = Auth::user()->managerProjects();
        }
        else{
             $projects = Auth::user()->reportableProjects();
        }
		
    	return view('evaluations.performance',compact('projects'));
    }
}
