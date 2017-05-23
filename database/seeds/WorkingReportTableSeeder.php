<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class WorkingReportTableSeeder extends Seeder
{
	use SeederHelpers;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$faker           = Faker::create();
		
		$users           = $this->users();
		$projects        = $this->projects();
		$absences        = $this->absences();
		
		$activities      = config('options.activities');
		$jobTypes        = config('options.typeOfJob');
		$trainingOptions = config('options.training');
		
		$today           = SeederConfig::TODAY();
		$timeSlots       = SeederConfig::TIME_SLOTS;

		foreach ($users as $user) {
			foreach ($activities as $activity) {
				
				if($activity == 'project') {		
					$project        = $faker->randomElement($projects);
					$groups         = $this->groups($project);
					$group          = $faker->randomElement($groups);
					$categoriesUser = $this->categoryUser($user);
					$category       = $faker->randomElement($categoriesUser);
					
					$projectId      = $project;
					$groupId        = $group;	
					$categoryId     = $category;
					$trainingType   = null;
					$courseGroupId  = null;			
					$absenceId      = null;
					$jobType        = $faker->randomElement($jobTypes);
				}
				elseif ($activity == 'absence') {
					$projectId     = null;
					$groupId       = null;
					$categoryId    = null;
					$trainingType  = null;
					$courseGroupId = null;	
					$absenceId     = $faker->randomElement($absences);	
					$jobType       = null;
				}
				elseif ($activity == 'training') {
					$projectId     = null;
					$groupId       = null;
					$categoryId    = null;
					$trainingType  = $faker->randomElement($trainingOptions);
					$courseGroupId = null;	
					$absenceId     = null;	
					$jobType       = null;
				}
				//Comentarios
				$comments = $faker->text(100);

				//Validaciones
				$pmValidation = $faker->boolean(50);
				if($pmValidation){
					$adminValidation = $faker->boolean(50);
				}
				else{
					$adminValidation = false;
				}


				DB::table('working_report')->insert([

					'user_id'          => $user,
					'created_at'       => $today,
					'activity'         => $activity,
					
					//Proyecto
					'project_id'       => $projectId,
					'group_id'         => $groupId,
					'category_id'      => $categoryId,
					
					//Formacion
					'training_type'    => $trainingType,
					'course_group_id'  => $courseGroupId,
					
					//Ausencia
					'absence_id'       => $absenceId,
					
					//General
					'time_slots'       => $timeSlots,
					'job_type'         => $jobType,
					'comments'         => $comments,
					
					//Validacion
					'pm_validation'    => $pmValidation,
					'admin_validation' => $adminValidation,
				
				]);  
			}
		} 
    }
}
