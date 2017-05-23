<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AttendeesTableSeeder extends Seeder
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
        
        $users        = $this->users();
        $courseGroups = $this->courseGroups();

        foreach ($users as $user) {    
        	for ($i = 1; $i <= count($courseGroups); $i++) { 

                $hours            = $courseGroups[$i]['hours'];
                $classHours       = SeederConfig::CLASS_HOURS;
                $classes          = $hours/$classHours;
                
                $loadworkAbsences = $faker->numberBetween(0,1);
                $otherAbsences    = $faker->numberBetween(0,1);
                $absences         = $loadworkAbsences + $otherAbsences;
                
                $participation    = (round(($classes - $absences)/$classes,4))*100;

	        	DB::table('attendees')->insert([
                    'user_id'           => $user,    
                    'group_id'          => $i,
                    'participation'     => $participation,
                    'open_invitation'   => $faker->boolean(50),
                    'loadwork_absences' => $loadworkAbsences,
                    //'project_id'      => $faker->boolean(50),
                    'other_absences'    => $otherAbsences,
                    'mark'              => $faker->randomFloat(2,40,100),
                    'comments'          => $faker->text(100),
	            ]); 
          	}   	
        }
    }
}
