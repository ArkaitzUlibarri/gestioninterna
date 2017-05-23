<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CalendarCourseTableSeeder extends Seeder
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
		$classHours   = SeederConfig::CLASS_HOURS;
		$courseGroups = $this->courseGroups();

		for ($j = 1; $j < count($courseGroups); $j++) { 
			$startDate = $courseGroups[$j]['start_date'];
			$endDate   = $courseGroups[$j]['end_date'];
			$hours     = $courseGroups[$j]['hours'];

			$classes = $hours/$classHours;

			for($i = 1; $i <= $classes ; $i++){

				if ($i == 1) {
					$date = Carbon::createFromFormat('Y-m-d',$startDate);
				}
				elseif ($i == $classes) {
					$date = Carbon::createFromFormat('Y-m-d',$endDate);
				}
				else{
					$date = Carbon::createFromFormat('Y-m-d',$startDate)->addDays($i-1);
				}

				$startTime = Carbon::createFromFormat('H:i:s',$faker->dateTimeBetween('-2 hours', '2 hours')->format('H:i:s'));
				$endTime   = $startTime->copy()->addHours($classHours);

				DB::table('calendar_course')->insert([
					'date'       => $date,
					'group_id'   => $j,
					'start_time' => $startTime,
					'end_time'   => $endTime,
        		]);	
			}		
		}   
    }
}
