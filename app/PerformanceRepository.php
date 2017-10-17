<?php

namespace App;

use App\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceRepository
{
    /**
	 * Devuelve una instancia del modelo del repositorio.
	 * 
	 * @return User
	 */
	public function getModel()
	{
		return new Performance;
	}

	/**
	 * Performance marks for a user in a year
	 * @param  [type] $year     [description]
	 * @param  [type] $employee [description]
	 * @return [type]           [description]
	 */
	public function performanceByUserYear($year,$employee)
	{
		return DB::table('performances')
			->select('id','project_id','type','month','mark','comment','weight')
			->where('user_id', $employee)//Usuario
			->where('year',$year)//Año
			->orderBy('type','ASC')
			->orderBy('month','ASC')
			->get();
	}

    /**
     * Format data.
     * 
     * @param  array
     * @return array
     */
	public function formatOutput($data)
	{
		if (count($data) == 0) {
            return null;
        }

        $result = array();
	
		foreach ($data as $index => $array) {
			$key = $array->project_id . '|' . $array->type . '|' . $array->month;

			if(! isset($result[$key])) {
                $result[$key] = [
					'id'      => $array->id,
					'mark'    => $array->mark,
					'comment' => ucfirst($array->comment),
					'weight'  => $array->weight
                ];
            }
		}
		
		return $result;
	}

}
