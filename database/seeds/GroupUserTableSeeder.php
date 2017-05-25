<?php

use Illuminate\Database\Seeder;

class GroupUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

	        [
				'user_id'  => 1,
				'group_id' => 1,
	    	],

	    	[
				'user_id'  => 1,
				'group_id' => 4,
	    	],

	    	[
				'user_id'  => 1,
				'group_id' => 5,
	    	],
	    	
	    	[
				'user_id'  => 1,
				'group_id' => 11,
	    	],
			
	    	[
				'user_id'  => 2,
				'group_id' => 1,
	    	],

	    	[
				'user_id'  => 2,
				'group_id' => 4,
	    	],

	    	[
				'user_id'  => 2,
				'group_id' => 5,
	    	],
	    	
	    	[
				'user_id'  => 2,
				'group_id' => 11,
	    	],
			/*
	    	[
				'user_id'  => 3,
				'group_id' => 14,
	    	],

	    	[
				'user_id'  => 3,
				'group_id' => 15,
	    	],

	    	[
				'user_id'  => 4,
				'group_id' => 14,
	    	],

	    	[
				'user_id'  => 4,
				'group_id' => 15,
	    	],
			*/
	    	[
				'user_id'  => 5,
				'group_id' => 1,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 2,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 3,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 4,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 6,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 7,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 8,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 9,
	    	],
	    	
	    	[
				'user_id'  => 5,
				'group_id' => 10,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 11,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 12,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 13,
	    	],
	    	/*
	    	[
				'user_id'  => 5,
				'group_id' => 14,
	    	],

	    	[
				'user_id'  => 5,
				'group_id' => 15,
	    	],
			*/
	    	[
				'user_id'  => 6,
				'group_id' => 1,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 2,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 3,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 5,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 6,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 7,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 8,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 9,
	    	],

	    	[
				'user_id'  => 6,
				'group_id' => 10,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 5,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 6,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 7,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 8,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 9,
	    	],

	    	[
				'user_id'  => 7,
				'group_id' => 10,
	    	],

	    	[
				'user_id'  => 8,
				'group_id' => 1,
	    	],

	    	[
				'user_id'  => 8,
				'group_id' => 2,
	    	],

	    	[
				'user_id'  => 8,
				'group_id' => 3,
	    	],
	    	
	    	[
				'user_id'  => 8,
				'group_id' => 11,
	    	],

	    	[
				'user_id'  => 8,
				'group_id' => 12,
	    	],

	    	[
				'user_id'  => 8,
				'group_id' => 13,
	    	],
			
	    	[
				'user_id'  => 9,
				'group_id' => 5,
	    	],

	    	[
				'user_id'  => 9,
				'group_id' => 6,
	    	],

	    	[
				'user_id'  => 9,
				'group_id' => 7,
	    	],

	    	[
				'user_id'  => 9,
				'group_id' => 8,
	    	],

	    	[
				'user_id'  => 9,
				'group_id' => 9,
	    	],

	    	[
				'user_id'  => 9,
				'group_id' => 10,
	    	],


       ];

		foreach ($data as $item) {
			DB::table('group_user')->insert([
				'user_id'       => $item['user_id'],
				'group_id'      => $item['group_id'],
			]); 	
		}			
    }
}
