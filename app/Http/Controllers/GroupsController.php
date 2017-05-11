<?php

namespace App\Http\Controllers;

use App\Group;
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
    /*
    public function show($id)
    {  
    	$group=$this->getGroup($id);

    	return view('groups.show',compact('group'));
    }

    public function edit($id)
    {
        $group=$this->getGroup($id);
        $projects=$this->getProjects();

        return view('groups.edit',compact('group','projects'));
    }
    */
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
    /*
    private function getGroup($id)
    {
    	return DB::table('groups')
    	    ->join('projects','groups.project_id','=','projects.id')
    	    ->where('groups.id',$id)
    		->select(
    			'projects.name as project',
    			'groups.id',
    			'groups.name',
    			'groups.enabled'
    		)
    		->first();
    }

    private function getProjects()
    {   	
    	return DB::table('projects')
    		->select('id','name')
    		->orderBy('name')
            ->get();
    }   
    */
}
