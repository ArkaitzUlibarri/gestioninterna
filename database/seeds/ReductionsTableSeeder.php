<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ReductionsTableSeeder extends Seeder
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

        $reductions=[
            [
                'contract_id' => 6,
                'start_date'  => $faker->dateTimeBetween('-4 months','-3 months'),
                'end_date'    => null,
                'week_hours'  => 35,
            ],

            [
                'contract_id' => 7,
                'start_date'  => $faker->dateTimeBetween('-4 months','-3 months'),
                'end_date'    => $faker->dateTimeBetween('-2 months','-1 months'),
                'week_hours'  => 30,
            ],

            [
                'contract_id' => 7,
                'start_date'  => $faker->dateTimeBetween('-1 months','-1 months'),
                'end_date'    => null,
                'week_hours'  => 25,
            ],

            [
                'contract_id' => 8,
                'start_date'  => $faker->dateTimeBetween('-3 months','-2 months'),
                'end_date'    => $faker->dateTimeBetween('-3 weeks','-2 weeks'),
                'week_hours'  => 25,
            ],

        ];

        foreach ($reductions as $reduction) {

			DB::table('reductions')->insert([
                'contract_id' => $reduction['contract_id'],
                'start_date'  => $reduction['start_date'],
                'end_date'    => $reduction['end_date'],
                'week_hours'  => $reduction['week_hours'],
			]); 	
		}
    }
}
