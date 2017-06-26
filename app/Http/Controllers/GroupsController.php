<?php

namespace App\Http\Controllers;

use App\Group;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole',['except' => ['index']]);
    }

    public function index()
    {  
    	$groups = $this->getGroups();

    	return view('groups.index', compact('groups'));
    }

    public function edit($id)
    {
        $project = Project::find($id);

        return view('groups.create', compact('project'));
    }

    public function editUser($user_id)
    {
        $user = User::find($user_id);
        $groupProjects = $this->getGroupsProjects();

        return view('groups.createUser',compact('user','groupProjects'));
    }
    
    private function getGroups()
    {
    	return DB::table('groups')
    		->join('projects','groups.project_id','=','projects.id')
    		->select(
    			'projects.name as project',
    			'groups.id',
    			'groups.name',
    			'groups.enabled'
    		)
    		->orderBy('project_id','asc')
    		->get();
    }

    private function getGroupsProjects()
    {
        $projects = Auth::user()->PMProjects();

        $q = DB::table('groups')
            ->select(
                'groups.project_id',
                'projects.name as project',
                'groups.id',
                'groups.name as group',
                'groups.enabled as enabled'
            )
            ->join('projects','groups.project_id','=','projects.id')
            //->where('groups.enabled',1)
            ->where('projects.end_date',null)
            ->orderBy('project_id','asc');

            if(!Auth::user()->isAdmin()){
                $q = $q->whereIn('projects.name',$projects);
            }
            
            return $q->get();
    }

}
