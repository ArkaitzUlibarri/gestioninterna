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

        $faker =Faker::create();
        $contractTypes=$this->contractTypes();
        $contracts=$this->contracts();

       	for ($j=1; $j <= count($contracts); $j++) { 
       		for ($i=1; $i <= count($contractTypes) ; $i++) { 
   			   	if($i==$contracts[$j]['contract_type_id']){

   			   		//Vacaciones por contrato
   					$contractHolidays=$contractTypes[$i];

   					//Datos del contrato
   					$startDate=$contracts[$j]['start_date'];
   					$estimatedEndDate=$contracts[$j]['estimated_end_date'];

   					//Años de los contratos
   					$startDateYear=Carbon::createFromFormat('Y-m-d',$startDate)->year;
   					$actualYear=intval(date('Y'));

   					for ($k=$startDateYear; $k <=$actualYear ; $k++) { 
   						$endOfYear= Carbon::createFromDate($k, 12, 31);
   						$startOfYear= Carbon::createFromDate($k, 1, 1);
	   					$daysOfYear=($endOfYear->copy()->dayOfYear)+1;

	   					if(intval($startDateYear)==$actualYear){

							if(! is_null($estimatedEndDate)){
								$days=(Carbon::createFromFormat('Y-m-d',$startDate)->diffInDays(Carbon::createFromFormat('Y-m-d',$estimatedEndDate)))+1;
							}
							else{
								$days=(Carbon::createFromFormat('Y-m-d',$startDate)->diffInDays($endOfYear))+1;
							}
	   						$holidays=round(($days/$daysOfYear)*$contractHolidays);	
	   						$used=round($holidays*0.2);

	   						$lastYear=$faker->numberBetween(0,5);
	   						$extras=$faker->numberBetween(0,2);
	   					}
	   					else{
	   						if($k==$actualYear){
	   							if(! is_null($estimatedEndDate)){
		   							$days=(Carbon::createFromDate($k, 1, 1)->diffInDays(Carbon::createFromFormat('Y-m-d',$estimatedEndDate)))+1;
	   							}
	   							else{
									$days=(Carbon::createFromDate($k, 1, 1)->diffInDays($endOfYear))+1;
								}
								$holidays=round(($days/$daysOfYear)*$contractHolidays);	
	   							$used=round($holidays*0.2);
								$lastYear=$faker->numberBetween(0,5);
	   							$extras=$faker->numberBetween(0,2);
	   						}
							else{
								$days=(Carbon::createFromFormat('Y-m-d',$startDate)->diffInDays($endOfYear))+1;

								$holidays=round(($days/$daysOfYear)*$contractHolidays);	
	   							$used=$holidays;
								$lastYear=0;
	   							$extras=0;
							}

	   					}
	   					
	   					DB::table('user_holidays')->insert([
							'contract_id'       => $j,
							'year'              => $k,
							'current_year'      => $holidays,
							'used_current_year' => $used,
							'last_year'         => $lastYear,
							'used_last_year'    => $lastYear,
							'extras'            => $extras,
							'used_extras'       => $extras,
						]); 					
   					}
       			}
       		}
		}
    }
}
