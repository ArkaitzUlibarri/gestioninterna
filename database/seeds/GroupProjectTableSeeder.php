<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GroupProjectTableSeeder extends Seeder
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

        $projects = [
			'MIND Ingenieria' => [
				'groups' => [
					'Gestión',
					'Tunning',
				]
			],
			'MEDIDAS WIFI MERCEDES' => [
				'groups' => [
					'-'
				]
			],
			'RAN EVO' => [
				'groups' => [
					'Gestión',
					'Tunning',
					'Diseño',
					'OPT',
					'RSSI',
				]
			],
			'ANE TFCA' => [
				'groups' => [
					'Gestión',
					'Diseño',
				]
			],
		];

		// Obtengo los usuarios con role igual a "user"
		$users = DB::Table('users')
			//->where('role', 'user')
			->select('id')->get()->toArray();
		
		$users = array_map( function($item) {
			return ['id' => $item->id, 'PM' => false];
		}, $users);

		$idProjectManager = DB::Table('categories')
			->where('code', 'rp')
			->select('id')
			->first()
			->id;

		$categorieIds = $this->userCategories();

		foreach ($projects as $projectName => $projectValues) {

			// Obtengo el ID del proyecto
			$project = DB::Table('projects')->where('name', $projectName)->select('id')->first();

			if($project->id == 3) {
				$users[0]['PM'] = true;
			}else{
				$users[0]['PM'] = false;
			}

			foreach ($projectValues['groups'] as $groupName) {

				// Completo la tabla pivot "groups 
				$groupId = $groupProjectId = DB::table('groups')->insertGetId([
					'project_id' => $project->id,
					'name'       => $groupName
				]);

				foreach ($users as $userItem) {

					DB::table('group_user')->insert([
						'group_id'    => $groupId,
						'user_id'     => $userItem['id'],
						//'category_id' => $userItem['PM'] == true ? $idProjectManager :  $faker->randomElement($categorieIds)
					]);
				}
			}
		}
    }
}
