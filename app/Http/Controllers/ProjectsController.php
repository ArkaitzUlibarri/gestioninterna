<?php

namespace App\Http\Controllers;

use App\Project;
use App\Customer;
use App\ProjectRepository;
use App\Http\Requests\ProjectFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectsController extends Controller
{
	protected $projectRepository;

	public function __construct(ProjectRepository $projectRepository)
	{
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

	/**
     * Remove the specified project from storage and his associated groups
     *
     * @param  int  $id
     * @return Response
     */
	public function destroy($id)
	{
		$project = Project::find($id);

		//Hay reportes del proyecto
		if($this->hasReportsAssociated($project)){
			return redirect('projects')->withErrors(['The project has reports associated']);
		}

		try{
	    	DB::beginTransaction();
			//******************************************************************************
			//Borrar tanto el proyecto como los grupos y group_user
			$project->delete();
			
			DB::table('group_user')->whereIn('group_id',array_pluck($project->groups,'id'))->delete();
			DB::table('groups')->where('project_id',$id)->delete();

			Session::flash('message', 'The project has been successfully deleted!');
			//******************************************************************************
			DB::commit();	// Transaction successful
		
		}catch(\Exception $e){       
		    DB::rollback();	// Transaction failed 
		    throw $e;
		}

		return redirect('projects');
	}

	/**
	 * Check if there are reports within the dates of the project
	 * @param  [type]  $project [description]
	 * @return boolean          [description]
	 */
	private function hasReportsAssociated($project)
	{	
		$q = DB::table('working_report')
				->where('created_at','>=',$project->start_date)
				->where('project_id',$project->id);

		if(! is_null($project->end_date)){
			$q = $q->where('created_at','<=',$project->end_date);
		}

		$q = $q->get();
		
		return ($q->count() > 0) ? true: false;
	}

	/**
	 * Obtain all the employees who can be PM in a project
	 * @return [type] [description]
	 */
	private function getPMs()
	{
		return DB::table('category_user as cu')
			->LeftJoin('categories as c','cu.category_id','=','c.id')
			->RightJoin('users as u','cu.user_id','=','u.id')
			->select(
				'cu.user_id as id',
				'u.name as username',
				'u.lastname as lastname',
				'u.role as role',
				DB::raw("CONCAT(u.name, ' ', u.lastname) as fullname"),
				'c.code as code'
			)
			->where('c.code','RP')//Responsable de Proyecto
			->orWhere('c.code','RTP')//Responsable Temporal de Proyecto
			->orWhere('c.code','DI')//Director
			->get();
	}
}
