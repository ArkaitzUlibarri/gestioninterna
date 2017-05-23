<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ContractsTableSeeder extends Seeder
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


		$users = [
			[  
				'user_id'            => 1,
				'contract_type_id'   => 3,
				'start_date'         => $faker->dateTimeBetween('-3 years', '-2 years'),
				'estimated_end_date' => $faker->dateTimeBetween('1 years', '2 years'),
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 2,
				'contract_type_id'   => 6,
				'start_date'         => $faker->dateTimeBetween('-6 months', '-5 months'),
				'estimated_end_date' => $estimated=$faker->dateTimeBetween('-2 months', '-1 months'),
				'end_date'           => $estimated,
				'national_days_id'   => 1,
				'regional_days_id'   => 3,
				'local_days_id'      => 6,
				'week_hours'         => 25,
			],

			[  
				'user_id'            => 2,
				'contract_type_id'   => 1,
				'start_date'         => $estimated,
				'estimated_end_date' => $faker->dateTimeBetween('3 months', '4 months'),
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 3,
				'contract_type_id'   => 2,
				'start_date'         => $faker->dateTimeBetween('-5 years', '-4 years'),
				'estimated_end_date' => null,
				'end_date'           => $faker->dateTimeBetween('-4 years', '-3 years'),
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],
			
			[  
				'user_id'            => 3,
				'contract_type_id'   => 3,
				'start_date'         => $faker->dateTimeBetween('-1 years', '-6 months'),
				'estimated_end_date' => $faker->dateTimeBetween('4 months', '5 months'),
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],
			
			[  
				'user_id'            => 4,
				'contract_type_id'   => 4,
				'start_date'         => $faker->dateTimeBetween('-5 months ', '4 months'),
				'estimated_end_date' => $faker->dateTimeBetween('6 months ', '7 months'),
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],
			
			[  
				'user_id'            => 5,
				'contract_type_id'   => 1,
				'start_date'         => $faker->dateTimeBetween('-5 years ', '-4 years'),
				'estimated_end_date' => null,
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 6,
				'contract_type_id'   => 1,
				'start_date'         => $faker->dateTimeBetween('-5 months', '-4 months'),
				'estimated_end_date' => null,
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 4,
				'local_days_id'      => 7,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 7,
				'contract_type_id'   => 1,
				'start_date'         => $faker->dateTimeBetween('-5 months', '-4 months'),
				'estimated_end_date' => null,
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 8,
				'contract_type_id'   => 1,
				'start_date'         => $faker->dateTimeBetween('-1 years', '-6 months'),
				'estimated_end_date' => null,
				'end_date'           => null,
				'national_days_id'   => 1,
				'regional_days_id'   => 3,
				'local_days_id'      => 6,
				'week_hours'         => 40,
			],

			[  
				'user_id'            => 9,
				'contract_type_id'   => 5,
				'start_date'         => $faker->dateTimeBetween('-1 years', '-6 months'),
				'estimated_end_date' => $faker->dateTimeBetween('-5 months', '-4 months'),
				'end_date'           => $faker->dateTimeBetween('-5 months', '-4 months'),
				'national_days_id'   => 1,
				'regional_days_id'   => 2,
				'local_days_id'      => 5,
				'week_hours'         => 35,
			],
			
		];

		foreach ($users as $user) {

			DB::table('contracts')->insert([
				'user_id'            => $user['user_id'],
				'contract_type_id'   => $user['contract_type_id'],
				'start_date'         => $user['start_date'],
				'estimated_end_date' => $user['estimated_end_date'],
				'end_date'           => $user['end_date'],
				'national_days_id'   => $user['national_days_id'],
				'regional_days_id'   => $user['regional_days_id'],
				'local_days_id'      => $user['local_days_id'],
				'week_hours'         => $user['week_hours'],
			]); 		
		}
	}
}



	