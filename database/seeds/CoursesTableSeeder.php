<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CoursesTableSeeder extends Seeder
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
    	
    	$availableYears = SeederConfig::AVAILABLE_YEARS;

    	$planes=$this->planes();

    	foreach ($availableYears as $year) {
			foreach($planes as $plan){
				DB::table('courses')->insert([
					'plan_id' => $plan,
					'year' => $year,
					'hobetuz' => $faker->numberBetween(0,3),
					'hobetuz_done' => $faker->numberBetween(0,3),
					'tripartita' => $faker->numberBetween(0,3),
					'without_subsidy' => $faker->numberBetween(0,3),
					'attendees' => $faker->numberBetween(0,15),
				]);
			}
    	}
    }
}
