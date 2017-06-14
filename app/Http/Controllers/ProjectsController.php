<?php

namespace App\Http\Controllers;

use App\Project;
use App\Customer;
use App\ProjectRepository;
use App\Http\Requests\ProjectFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
	protected $projectRepository;

	public function __construct(ProjectRepository $projectRepository)
	{
		$this->middleware('auth');
        //$this->middleware('checkrole',['except' => ['index']]);
        $this->middleware('checkrole');
		$this->projectRepository = $projectRepository;
	}

    public function index(Request $request)
    {
		$projects   = $this->projectRepository->search($request->all(), false);
		$customers  = Customer::all();

		$filter = array(	
			'name'  => $request->get('name'),
			'customer' => $request->get('customer'),
			'type'     => $request->get('type'),
		);

    	return view('projects.index',compact('projects','customers','filter'));
    }

    public function edit($id)
    {
		$project     = Project::find($id);
		$customers   = Customer::all();
		$PM_Users    = $this->getPMs();

    	return view('projects.edit',compact('project','customers','PM_Users'));
    }

    public function show($id)
    {
		$project   = Project::find($id);

    	return view('projects.show',compact('project'));
    }

    public function create()
    {
		$customers = Customer::all();
		$PM_Users  = $this->getPMs();

    	return view('projects.create',compact('customers','PM_Users'));
    }

    public function store(ProjectFormRequest $request)
	{		
		/*
		$project = new Project;
	    $project->fill($request->all());
	    $project->save();
	    */
	   
    	try{
	    	DB::beginTransaction();
			//******************************************************************************
		    $array = $request->all();
		    unset($array['_token']);

		    $id = DB::table('projects')->insertGetId($array);

			DB::table('groups')->insert(['project_id' =>$id, 'name' => 'Default','enabled'=> 1]);
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('/projects');
	}

	public function update(ProjectFormRequest $request, $id)
	{
		try{
	    	DB::beginTransaction();
			//******************************************************************************
			$project = Project::find($id);
			$project->update($request->all());
	        //**************************************************************
	        //Cierre de proyecto->Deshabilitar grupos
	        if($request->get('end_date') != null){
				$groups  = $project->groups->where('enabled','1');
				if($groups){
					foreach ($groups as $group) {
						DB::table('groups')
							->where('id',$group->id)
							->update(['enabled' => '0']);
					}
				}
			}
			else{
				//Proyecto Abierto->Habilitar Grupos
				DB::table('groups')
					->where('project_id',$id)
					->update(['enabled' => '1']);
			}
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('/projects');
	}

	private function getPMs()
	{
		return DB::table('category_user')
			->LeftJoin('categories','category_user.category_id','=','categories.id')
			->LeftJoin('users','category_user.user_id','=','users.id')
			->select(
				/*'category_user.category_id as category_id',
				'categories.name as name',
				'categories.code as code',
				'categories.description as description',*/
				'category_user.user_id as id',
				'users.name as username',
				'users.lastname_1 as lastname_1',
				'users.lastname_2 as lastname_2',
				'users.role as role',
				DB::raw("CONCAT(users.name, ' ', users.lastname_1) as fullname")
			)
			->where('code','RP')
			->orWhere('code','RTP')
			->groupBy('user_id')
			->get();
	}
}
