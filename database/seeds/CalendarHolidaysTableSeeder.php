<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CalendarHolidaysTableSeeder extends Seeder
{
	use SeederHelpers;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $faker        = Faker::create();
        $userHolidays = $this->userHolidays();

        $k = 0;

        for ($i = 1; $i <= count($userHolidays) ; $i++) { 
            $usedCurrentYear  = $userHolidays[$i]['used_current_year'];
            $usedLastYear     = $userHolidays[$i]['used_last_year'];
            $usedExtras       = $userHolidays[$i]['used_extras'];
            $contractID       = $userHolidays[$i]['contract_id'];
            $user             = $userHolidays[$i]['user_id'];
            $startDate        = $userHolidays[$i]['start_date'];
            $estimatedEndDate = $userHolidays[$i]['estimated_end_date'];
       
            $startDateYear    = Carbon::createFromFormat('Y-m-d',$startDate)->year;
            $actualYear = intval(date('Y'));

            if($startDateYear != $actualYear){
                $startDate = Carbon::createFromDate($actualYear, 01, 01);
            }

            if(is_null($estimatedEndDate)){
                $endDate = Carbon::createFromDate($actualYear, 12, 31)->copy();
            }
            else{
                $endDate = Carbon::createFromFormat('Y-m-d',$estimatedEndDate);
            }

            $types = ['current_year','last_year','extras'];
            $quantities = [
                'current_year' => $usedCurrentYear,
                'last_year'    => $usedLastYear,
                'extras'       => $usedExtras,
            ];

            $date = Carbon::createFromFormat('Y-m-d',$faker->dateTimeBetween( $startDate, $endDate)->format('Y-m-d'));
            
            foreach ($types as $type) {

                $quantityType = $quantities[$type];
               
                for ($j = 1; $j <= $quantityType ; $j++) {  

                    $array = [
                        'user_id'   => $user,    
                        'date'      => $date->copy()->addDays($k),
                        'type'      => $type,
                        'comments'  => $faker->text(20),
                        'validated' => $faker->boolean(80),
                    ];

                    var_dump($array);
                    //DB::table('calendar_holidays')->insert($array);      
                                   
                    $k++;
                }

            }
        }
        
    }
}