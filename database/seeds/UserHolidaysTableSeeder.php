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
		$contracts = $this->contracts();
	
       	for ($j = 1; $j <= count($contracts); $j++) { 
       		
		   	//Vacaciones por contrato
			$contractHolidays = $contracts[$j]['holidays'];

			//Datos del contrato
			$startDate = $contracts[$j]['start_date'];
			$estimatedEndDate = $contracts[$j]['estimated_end_date'];

			//Años
			$actualYear = intval(date('Y'));
			$daysOfYear = Carbon::createFromDate($actualYear, 12, 31)->dayOfYear;
			$startDateYear = Carbon::createFromFormat('Y-m-d',$startDate)->year;
			if(is_null($estimatedEndDate)){
				$estimatedEndDateYear = null;
			}
			else{
				$estimatedEndDateYear = Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->year;
			}
		
			//Indefinido o fin de obra empezado otro año
			if(is_null($estimatedEndDate)){
				if($startDateYear != $actualYear)
				{
					$holidays = $contractHolidays;
					$lastYear = $faker->numberBetween(0,5);
					$extras = $faker->numberBetween(0,2);
				}
				else{
					$days = Carbon::createFromFormat('Y-m-d',$startDate)->dayOfYear;

					$holidays =	round(($days/$daysOfYear)*$contractHolidays);
					$lastYear = 0;
					$extras = 0;
				}
			}
			else{
				if($estimatedEndDateYear == $actualYear && $startDateYear != $actualYear ){
					$days = Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->dayOfYear;

					$holidays =	round(($days/$daysOfYear)*$contractHolidays);
					$lastYear = $faker->numberBetween(0,5);
					$extras = $faker->numberBetween(0,2);
				}	
				else{
					$startDay = Carbon::createFromFormat('Y-m-d',$startDate)->dayOfYear;
					$estimatedEndDay = Carbon::createFromFormat('Y-m-d',$estimatedEndDate)->dayOfYear;

					$holidays =	round((($estimatedEndDay - $estimatedEndDay) /$daysOfYear)*$contractHolidays);
					$lastYear = 0;
					$extras = 0;
				}	
			}

			$used = round($holidays*0.2, 0);		

			$array = [
				'contract_id'       => $j,
				'year'              => $actualYear,
				'current_year'      => $holidays,
				'used_current_year' => $used,
				'last_year'         => $lastYear,
				'used_last_year'    => $lastYear,
				'extras'            => $extras,
				'used_extras'       => $extras,
			];

			DB::table('user_holidays')->insert($array); 			 			       		
		}
	
    }
}
