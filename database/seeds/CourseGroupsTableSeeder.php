<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CourseGroupsTableSeeder extends Seeder
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

        $start=SeederConfig::START_DATE();
        $end=SeederConfig::END_DATE();

        $courses = $this->courses();

        $array = DB::table('planes')
            ->select('id', 'hours')
            ->get();

        $planes = array();

        foreach ($array as $plan) {
            $planes[$plan->id] = $plan->hours;
        }


        for ($j=1; $j <count($courses); $j++) { 
            $courseGroups=$faker->numberBetween(1,4);
            $hours=$planes[$courses[$j]];

            for ($i=1; $i<$courseGroups; $i++) {

                $startDate = Carbon::createFromFormat('Y-m-d',$faker->dateTimeBetween('-3 months', '3 months')->format('Y-m-d'));
                $endDate = $startDate->copy()->addWeeks(2);

                DB::table('course_groups')->insert([
                    'course_id'  => $j,
                    'name'       => "g".strval($i),
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'hours'      => $hours,
                ]);
            }
        }    
    }
}

