<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contract_types = [
            ['code' =>'100','name'=>'Indefinido','holidays'=>22],
            ['code' =>'401','name'=>'Obra o servicio','holidays'=>22],
            ['code' =>'402','name'=>'Eventual (circunstancias de la producción)','holidays'=>22],
            ['code' =>'420','name'=>'Prácticas','holidays'=>22],
            ['code' =>'bl','name'=>'Beca lanbide','holidays'=>0],
            ['code' =>'bce','name'=>'Beca cooperación educativa','holidays'=>15],
            ['code' =>'fp','name'=>'Prácticas formación profesional','holidays'=>0],
            ['code' =>'so','name'=>'Socio','holidays'=>22],
        ];

        /*
            $contract_types = [
                ['code' =>'100','name'=>'Indefinite','holidays'=>22],
                ['code' =>'401','name'=>'Work or service','holidays'=>22],
                ['code' =>'402','name'=>'Eventual (production circumstances)','holidays'=>22],
                ['code' =>'420','name'=>'Apprenticeship','holidays'=>22],
                ['code' =>'bl','name'=>'Lanbide's grant,'holidays'=>0],
                ['code' =>'bce','name'=>'Educative cooperation Grant','holidays'=>15],
                ['code' =>'fp','name'=>'Profesional formation internship','holidays'=>0],
            ];
        */

        foreach ($contract_types as $type) {
            DB::table('contract_types')->insert([
                'code' => $type['code'],
                'name' => $type['name'],
                'holidays' => $type['holidays'],
            ]);
        }
    }
}
