<?php

namespace App\Http\Controllers;

use App\Group;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole');
    }

    public function index()
    {  
    	$groups=$this->getGroups();

    	return view('groups.index',compact('groups'));
    }

    public function edit($id)
    {

        $project=Project::find($id);

        return view('projects.addgroup',compact('project'));
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

}
