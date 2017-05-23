<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoryUserTableSeeder extends Seeder
{
	use SeederHelpers;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $data = [

	        [
				'user_id'     => 1,
				'category_id' => 16,
	    	],

	    	[
				'user_id'     => 1,
				'category_id' => 6,
	    	],

	    	[
				'user_id'     => 2,
				'category_id' => 15,
	    	],

	    	[
				'user_id'     => 3,
				'category_id' => 22,
	    	],

	    	[
				'user_id'     => 4,
				'category_id' => 21,
	    	],

	    	[
				'user_id'     => 5,
				'category_id' => 1,
	    	],

	    	[
				'user_id'     => 5,
				'category_id' => 3,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 2,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 3,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 4,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 5,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 6,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 7,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 12,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 13,
	    	],

	    	[
				'user_id'     => 6,
				'category_id' => 14,
	    	],

	    	[
				'user_id'     => 7,
				'category_id' => 17,
	    	],

	    	[
				'user_id'     => 7,
				'category_id' => 18,
	    	],

	    	[
				'user_id'     => 7,
				'category_id' => 19,
	    	],

	    	[
				'user_id'     => 7,
				'category_id' => 20,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 4,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 5,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 6,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 9,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 10,
	    	],

	    	[
				'user_id'     => 8,
				'category_id' => 11,
	    	],

	    	[
				'user_id'     => 9,
				'category_id' => 23,
	    	],

       ];

		foreach ($data as $item) {
			DB::table('category_user')->insert([
				'user_id'          => $item['user_id'],
				'category_id'      => $item['category_id'],
			]); 	
		}			
    }
}
