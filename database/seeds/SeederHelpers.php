<?php

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

trait SeederHelpers
{
	/**
	 * Obtengo un array de los ids de los usuarios
	 * @return  array 
	 */
	private function users()
	{
		$array = DB::table('users')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}

	/**
	 * Obtengo un array de los ids de los proyectos
	 * @return  array 
	 */
	private function projects()
	{
		$array = DB::table('projects')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}

	/**
	 * Obtengo un array de los ids de los proyectos
	 * @return  array 
	 */
	private function groups($projectId)
	{
		$array = DB::table('groups')
			->select('id')
			->where('project_id',$projectId)
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}


	/**
	 * Obtengo un array de los ids de los proyectos
	 * @return  array 
	 */
	private function absences()
	{
		$array = DB::table('absences')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}

	private function categories()
	{
		$array = DB::table('categories')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}

	private function categoryUser($userId)
	{
		$array = DB::table('category_user')
			->select('category_id')
			->where('user_id',$userId)
			->orderBy('category_id')
			->get();

		return array_pluck($array,'category_id');	
	}

	private function userCategories()
	{
		$array = DB::table('categories')
			->where('code', '!=', 'di')
			->where('code', '!=', 'rp')
			->where('code', '!=', 'ds')
			->where('code', '!=', 'ad')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}


	/**
	 * Obtengo un array de los ids de los planes
	 * @return  array 
	 */
	private function planes()
	{
		$array = DB::table('planes')
			->select('id')
			->orderBy('id')
			->get();

		return array_pluck($array,'id');	
	}

	/**
	 * Obtengo un array de los ids y planes de los cursos
	 * @return  array 
	 */
	private function courses()
	{
		$array = DB::table('courses')
			->select('id','plan_id')
			->get();

		$courses = array();

        foreach ($array as $course) {
             $courses[$course->id] = $course->plan_id;
        }	
        return $courses;
	}

	/**
	 * Obtengo un array de los ids de los grupos
	 * @return  array 
	 */
	private function courseGroups()
	{
		$array = DB::table('course_groups')
			->select('id','start_date','end_date','hours')
			->get();

		$courseGroups=array();
		
		foreach ($array as $courseGroup) {
             $courseGroups[$courseGroup->id] = [
				'start_date' => $courseGroup->start_date,
				'end_date'   => $courseGroup->end_date,
				'hours'      => $courseGroup->hours
             ];
        }	
		return $courseGroups;	
	}

	/**
	 * Obtengo un array de los ids de los planes
	 * @return  array 
	 */
	private function contractTypes()
	{
		$array = DB::table('contract_types')
			->select('id','holidays')
			->get();

		$contractTypes=array();

		foreach ($array as $contractType) {
	         $contractTypes[$contractType->id] = $contractType->holidays;
        }	

		return $contractTypes;	
	}

	/**
	 * Obtengo un array de la informacion de los contratos
	 * @return  array 
	 */
	private function contracts()
	{
		$array = DB::table('contracts')
			->select('id','user_id','start_date','estimated_end_date','end_date','week_hours','contract_type_id')
			->get();

		$contracts = array();
		
		foreach ($array as $contract) {
	         $contracts[$contract->id] = [
				'user_id'            => $contract->user_id,
				'start_date'         => $contract->start_date,
				'estimated_end_date' => $contract->estimated_end_date,
				'end_date'           => $contract->end_date,
				'week_hours'         => $contract->week_hours,
				'contract_type_id'   => $contract->contract_type_id
	         ];
        }	
		return $contracts;	
	}

	/**
	 * Obtengo un array de la informacion de las vacaciones pedidas
	 * @return  array 
	 */
	private function userHolidays()
	{
		$array = DB::table('user_holidays')
			->select('contract_id','used_current_year','used_last_year','used_extras')
			->get();

		$holidays = array();
		$i = 0;
		foreach ($array as $holiday) {
			$i++;
	         $holidays[$i] = [
				'contract_id'       => $holiday->contract_id,
				'used_current_year' => $holiday->used_current_year,
				'used_last_year'    => $holiday->used_last_year,
				'used_extras'       => $holiday->used_extras,
	         ];
        }	
		return $holidays;	
	}
}