<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserHolidaysTableSeeder extends Seeder
{
	use SeederHelpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$faker = Faker::create();
		$actualYear = intval(date('Y'));//Año actual
		$contracts = $this->contracts();
	
       	for ($contract_id = 1; $contract_id <= count($contracts); $contract_id++) { 
       			
       		$current_year = $this->getThisYearHolidays($contracts,$contract_id,$actualYear);	

			$array = [
				'contract_id'       => $contract_id,
				'year'              => $actualYear,
				'current_year'      => $current_year,
				'used_current_year' => 0,//round($current_year * 0.2, 0),
				'last_year'         => 0,//$lastYear,
				'used_last_year'    => 0,//$lastYear,
				'extras'            => 0,//$extras,
				'used_extras'       => 0,//$extras,
				'used_next_year'    => 0,//$extras,
			];

			DB::table('user_holidays')->insert($array); 			 			       		
		}	
    }

    private function getThisYearHolidays($contracts,$id,$year)
    {
		//fechas del contrato
		$startDate = $contracts[$id]['start_date'];//Fecha de comienzo
		$estimatedEndDate = $contracts[$id]['estimated_end_date'];//Fecha estimada de fin

		//Años
		$startDateYear = Carbon::createFromFormat('Y-m-d',$startDate)->year;//Año Comienzo contrato
		$estimatedEndDateYear = is_null($estimatedEndDate) ? null : Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->year;//Año Estimado de fin

		//Dias
		$contractHolidays = $contracts[$id]['holidays'];//Dias de vacaciones por contrato
		$daysOfYear = Carbon::createFromDate($year, 12, 31)->dayOfYear;//Dias del año
	
		//Indefinido o fin de obra empezado otro año
		if(is_null($estimatedEndDate)){
			if($startDateYear != $year)
			{
				//Distinto año de comienzo
				$current_year = $contractHolidays;//22
				//$lastYear = $faker->numberBetween(0,5);
				//$extras = $faker->numberBetween(0,2);
			}
			else{
				//Mismo año->Calculo de días
				$startDay = Carbon::createFromFormat('Y-m-d',$startDate)->dayOfYear;
				$current_year =	round(($daysOfYear - $startDay/$daysOfYear) * $contractHolidays);
				//$lastYear = 0;
				//$extras = 0;
			}
		}
		else{
			if($startDateYear != $year && $estimatedEndDateYear == $year){
				$days = Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->dayOfYear;
				$current_year =	round(($days / $daysOfYear) * $contractHolidays);
				//$lastYear = $faker->numberBetween(0,5);
				//$extras = $faker->numberBetween(0,2);
			}	
			else if($startDateYear == $year && $estimatedEndDateYear == $year){
				$startDay = Carbon::createFromFormat('Y-m-d',$startDate)->dayOfYear;//Dia de comienzo
				$estimatedEndDay = Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->dayOfYear;//Dia de Fin
				$days = $estimatedEndDay - $startDay;
				$current_year =	round(($days / $daysOfYear) * $contractHolidays);
				//$lastYear = 0;
				//$extras = 0;
			}	
			else if($startDateYear == $year && $estimatedEndDateYear != $year){
				$startDay = Carbon::createFromFormat('Y-m-d',$startDate)->dayOfYear;//Dia de comienzo
				$days = $daysOfYear - $startDay;
				$current_year =	round(($days / $daysOfYear) * $contractHolidays);
				//$lastYear = 0;
				//$extras = 0;
			}
			else if($startDateYear != $year && $estimatedEndDateYear != $year){
				$current_year = $contractHolidays;//22
				//$lastYear = 0;
				//$extras = 0;
			}		
		}

		return $current_year;
    }
}