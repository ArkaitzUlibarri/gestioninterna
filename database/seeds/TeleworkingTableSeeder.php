<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TeleworkingTableSeeder extends Seeder
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

        $teleworkingData = [

            [
				'contract_id' => 1,
				'start_date'  => $faker->dateTimeBetween('-1 year','-1 year'),
				'end_date'    => $faker->dateTimeBetween('-4 months','-4 months'),
				'monday'      => $faker->boolean(100),
				'tuesday'     => $faker->boolean(10),
				'wednesday'   => $faker->boolean(20),
				'thursday'    => $faker->boolean(30),
				'friday'      => $faker->boolean(10),
				'saturday'    => $faker->boolean(10),
				'sunday'      => $faker->boolean(10),
        	],

        	[
				'contract_id' => 7,
				'start_date'  => $faker->dateTimeBetween('-1 year','-11 months'),
				'end_date'    => $faker->dateTimeBetween('-3 month','-1 month'),
				'monday'      => $faker->boolean(100),
				'tuesday'     => $faker->boolean(10),
				'wednesday'   => $faker->boolean(20),
				'thursday'    => $faker->boolean(30),
				'friday'      => $faker->boolean(10),
				'saturday'    => $faker->boolean(10),
				'sunday'      => $faker->boolean(10),
        	],

        	[
				'contract_id' => 7,
				'start_date'  => $faker->dateTimeBetween('-1 month','-1 week'),
				'end_date'    => null,
				'monday'      => $faker->boolean(100),
				'tuesday'     => $faker->boolean(10),
				'wednesday'   => $faker->boolean(20),
				'thursday'    => $faker->boolean(30),
				'friday'      => $faker->boolean(10),
				'saturday'    => $faker->boolean(10),
				'sunday'      => $faker->boolean(10),
        	],

        	[
				'contract_id' => 10,
				'start_date'  => $faker->dateTimeBetween('-10 months','-6 months'),
				'end_date'    => null,
				'monday'      => $faker->boolean(100),
				'tuesday'     => $faker->boolean(10),
				'wednesday'   => $faker->boolean(20),
				'thursday'    => $faker->boolean(30),
				'friday'      => $faker->boolean(10),
				'saturday'    => $faker->boolean(10),
				'sunday'      => $faker->boolean(10),
        	],

        ];

		foreach ($teleworkingData as $item) {
			DB::table('teleworking')->insert([
				'contract_id' => $item['contract_id'],
				'start_date'  => $item['start_date'],
				'end_date'    => $item['end_date'],
				'monday'      => $item['monday'],
				'tuesday'     => $item['tuesday'],
				'wednesday'   => $item['wednesday'],
				'thursday'    => $item['thursday'],
				'friday'      => $item['friday'],
				'saturday'    => $item['saturday'],
				'sunday'      => $item['sunday'],

			]); 					
		}		
    }
}
