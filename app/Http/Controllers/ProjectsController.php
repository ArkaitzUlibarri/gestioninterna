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
		$this->middleware('checkrole');
		$this->projectRepository = $projectRepository;
	}

    public function index(Request $request)
    {
		//$projects = $this->getProjects();
		$projects   = $this->projectRepository->search($request->all(), true);
		$customers  = Customer::all();
    	return view('projects.index',compact('projects','customers'));
    }

    public function edit($id)
    {
		//$project   = $this->getProject($id);
		//$customers = $this->customers();	
		$project     = Project::find($id);
		$customers   = Customer::all();
		$PM_Users    = $this->getPMs();
    	return view('projects.edit',compact('project','customers','PM_Users'));
    }

    public function show($id)
    {
		//$project = $this->getProject($id);
		$project   = Project::find($id);
    	return view('projects.show',compact('project'));
    }

    public function create()
    {
		$customers = $this->customers();
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

			DB::table('groups')->insert(['project_id' =>$id, 'name' => '-','enabled'=> 1]);
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       

		    DB::rollback(); /* Transaction failed. */ 
		}

		return redirect('/projects');
	}

	public function update(ProjectFormRequest $request, $id)
	{
		$project = Project::find($id);

		$project->update($request->all());

		return redirect('/projects');
	}

	private function customers()
	{
		return DB::table('customers')
    		->select('id','name')
    		->get();
	}

	private function getProject($id)
	{
		return DB::table('projects')
			->join('customers','projects.customer_id','=','customers.id')
			->where('projects.id', $id)
			->select(
				'projects.*',
				'customers.name as customerName'
			)
			->first();
	}

	private function getProjects()
	{
		return DB::table('projects')
			->join('customers','projects.customer_id','=','customers.id')	
			->select(
				'projects.*',
				'customers.name as customer'
			)
			//->where('end_date',null)
			->orderBy('start_date','asc')
			->get();
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
