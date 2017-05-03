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
		$this->projectRepository = $projectRepository;
	}

    public function index(Request $request)
    {
    	//$projects=$this->getProjects();
    	$projects = $this->projectRepository->search($request->all(), true);
    	$customers=Customer::all();
    	return view('projects.index',compact('projects','customers'));
    }

    public function edit($id)
    {
    	//$project=$this->getProject($id);
    	$project = Project::find($id);
    	$customers=Customer::all();
		//$customers=$this->customers();	
    	return view('projects.edit',compact('project','customers'));
    }

    public function show($id)
    {
		//$project=$this->getProject($id);
		$project = Project::find($id);
    	return view('projects.show',compact('project'));
    }

    public function create()
    {
    	$customers=$this->customers();
    	return view('projects.create',compact('customers'));
    }

    public function store(ProjectFormRequest $request)
	{		
		$project = new Project;
		
        $project->fill($request->all());

        $project->save();

		return redirect('/projects');
	}

	public function update(ProjectFormRequest $request, $id)
	{
		$project=Project::find($id);

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
}
