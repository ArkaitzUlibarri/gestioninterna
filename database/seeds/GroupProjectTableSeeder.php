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
					'Default',
					'Gestión',
					'Tunning',
				]
			],
			'MEDIDAS WIFI MERCEDES' => [
				'groups' => [
					'Default'
				]
			],
			'RAN EVO' => [
				'groups' => [
					'Default',
					'Gestión',
					'Tunning',
					'Diseño',
					'OPT',
					'RSSI',
				]
			],
			'ANE TFCA' => [
				'groups' => [
					'Default',
					'Gestión',
					'Diseño',
				]
			],
		];

		foreach ($projects as $projectName => $projectValues) {

			// Obtengo el ID del proyecto
			$project = DB::Table('projects')->where('name', $projectName)->select('id')->first();

			foreach ($projectValues['groups'] as $groupName) {

				// Completo la tabla pivot "groups 
				$groupId = $groupProjectId = DB::table('groups')->insertGetId([
					'project_id' => $project->id,
					'name'       => $groupName
				]);
			}
		}
    }
}
