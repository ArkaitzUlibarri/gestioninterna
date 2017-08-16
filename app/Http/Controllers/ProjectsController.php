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
		$projects = $this->projectRepository->search($request->all(), true);
		$customers = Customer::all();

		$filter = array(	
			'name' => $request->get('name'),
			'customer' => $request->get('customer'),
			'type' => $request->get('type'),
		);

    	return view('projects.index',compact('projects','customers','filter'));
    }

    public function edit($id)
    {
		$project = Project::find($id);
		$customers = Customer::all();
		$PM_Users = $this->getPMs();

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
    	try{
	    	DB::beginTransaction();
			//******************************************************************************
		    $array = $request->all();
		    unset($array['_token']);

		    $project_id = DB::table('projects')->insertGetId($array);//Insertar Proyecto
			$group_id = DB::table('groups')->insertGetId(['project_id' => $project_id, 'name' => 'Default','enabled'=> 1]);//Crear Grupo por Defecto
			DB::table('group_user')->insert(['user_id' => $array['pm_id'], 'group_id' => $group_id]);//Asignarle el grupo por defecto al PM
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('projects');
	}

	public function update(ProjectFormRequest $request, $id)
	{
		try{
	    	DB::beginTransaction();
			//******************************************************************************
			$project = Project::find($id);

			$end = $project->end_date;//Anterior Fecha fin de proyecto
			$pm_id = $project->pm_id;//Anterior PM
			$groups_id = array_pluck($project->groups,'id');//Grupos del proyecto
			$pm_groups = array_pluck(DB::table('group_user')->select('group_id')->where('user_id',$request->get('pm_id'))->get(),'group_id');

			$project->update($request->all());
	        //******************************************************************************
	        //Cambio de fecha fin de proyecto
	        if($end != $request->get('end_date')){
		       	//Cierre de proyecto->Deshabilitar grupos
		        if($request->get('end_date') != null){
					$groups  = $project->groups->where('enabled','1');
					if($groups){
						foreach ($groups as $group) {
							DB::table('groups')->where('id',$group->id)->update(['enabled' => '0']);
						}
					}
				}
				else{
					//Abrir Proyecto->Habilitar Grupos
					DB::table('groups')->where('project_id',$id)->update(['enabled' => '1']);
				}
	        }
			//******************************************************************************
			//Cambio de PM
			if($pm_id != $request->get('pm_id')){

				$array = [];

				//Array de grupos al que añadir el PM
				foreach ($groups_id as $group) {
					array_push($array, ['group_id' => $group, 'user_id' => $request->get('pm_id')]);
				}

				//Filtrar por aquellos en los que esta ya
				$array = array_filter($array, function($item) use($pm_groups){
					if(! in_array($item['group_id'], $pm_groups)){
						return true;
					}
				});
				
				if(count($array)){
					DB::table('group_user')->insert($array);//Asignarle los grupos de este proyecto al PM nuevo
				}	
			}
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('projects');
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
				'users.lastname as lastname',
				'users.role as role',
				DB::raw("CONCAT(users.name, ' ', users.lastname) as fullname")
			)
			->where('code','RP')
			->orWhere('code','RTP')
			->groupBy('user_id')
			->get();
	}
}
