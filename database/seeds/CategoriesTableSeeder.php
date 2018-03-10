<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' =>'DI', 'code' => 'DI', 'description' => 'Director'],
            ['name' =>'RP Junior', 'code' => 'RP', 'description' => 'Responsable de Proyecto'],
            ['name' =>'RP Senior', 'code' => 'RP', 'description' => 'Responsable de Proyecto'],
            ['name' =>'RTP', 'code' => 'RTP', 'description' => 'Responsable Temporal de Proyecto'],
            ['name' =>'RG Junior', 'code' => 'RG', 'description' => 'Responsable de Grupo'],
            ['name' =>'RG Intermediate', 'code' => 'RG', 'description' => 'Responsable de Grupo'],
            ['name' =>'RG Senior', 'code' => 'RG', 'description' => 'Responsable de Grupo'],
            ['name' =>'LT', 'code' => 'LT', 'description' => 'Lider Tecnico'],
            ['name' =>'IP Junior', 'code' => 'IP', 'description' => 'Ingeniero de Proyecto'],
            ['name' =>'IP Intermediate', 'code' => 'IP', 'description' => 'Ingeniero de Proyecto'],
            ['name' =>'IP Senior', 'code' => 'IP', 'description' => 'Ingeniero de Proyecto'],
            ['name' =>'ITP Junior', 'code' => 'ITP', 'description' => 'Ingeniero Tecnico de Proyecto'],
            ['name' =>'ITP Intermediate', 'code' => 'ITP', 'description' => 'Ingeniero Tecnico de Proyecto'],
            ['name' =>'IP Senior', 'code' => 'ITP', 'description' => 'Ingeniero Tecnico de Proyecto'],
            ['name' =>'DS Junior', 'code' => 'DS', 'description' => 'Desarrollador de Software'],
            ['name' =>'DS Experienced', 'code' => 'DS', 'description' => 'Desarrollador de Software'],
            ['name' =>'TP Junior', 'code' => 'TP', 'description' => 'Tecnico de Proyecto'],
            ['name' =>'TP Experienced', 'code' => 'TP', 'description' => 'Tecnico de Proyecto'],
            ['name' =>'AT Junior', 'code' => 'AT', 'description' => 'Auxiliar Tecnico'],
            ['name' =>'TP Experienced', 'code' => 'AT', 'description' => 'Auxiliar Tecnico'],
            ['name' =>'AD Junior', 'code' => 'AD', 'description' => 'Administrativo'],
            ['name' =>'AD Experienced', 'code' => 'AD', 'description' => 'Administrativo'],
            ['name' =>'BC', 'code' => 'BC', 'description' => 'Becario'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name'        => $category['name'],
                'code'        => $category['code'],
                'description' => $category['description'],
            ]);
        }
    }
}
