<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
            $absences=[
                ['code' =>'m','group'=>'baja o enfermedad','name'=>'medico'],
                ['code' =>'m','group'=>'baja o enfermedad','name'=>'baja medica'],
                ['code' =>'b','group'=>'permiso','name'=>'boda'],
                ['code' =>'p','group'=>'permiso','name'=>'dia previo a examen'],
                ['code' =>'e','group'=>'permiso','name'=>'examen'],
                ['code' =>'mud','group'=>'permiso','name'=>'mudanza'],
                ['code' =>'f','group'=>'permiso','name'=>'permiso por asunto familiar'],
                ['code' =>'o','group'=>'otros','name'=>'otros'],
                ['code' =>'pend','group'=>'otros','name'=>'pendiente de reportar'],
                ['code' =>'v','group'=>'vacaciones','name'=>'vacaciones'],
            ];
        */

        $absences = [
            ['code' =>'m','group'=>'sickness','name'=>'medical leave'],
            ['code' =>'s','group'=>'sickness','name'=>'sick absence'],
            ['code' =>'w','group'=>'permission','name'=>'wedding'],
            ['code' =>'ee','group'=>'permission','name'=>'exam eve'],
            ['code' =>'e','group'=>'permission','name'=>'exam'],
            ['code' =>'r','group'=>'permission','name'=>'removal'],
            ['code' =>'f','group'=>'permission','name'=>'family bussiness leave'],
            ['code' =>'o','group'=>'others','name'=>'others'],
            ['code' =>'p','group'=>'others','name'=>'pending to report'],
            ['code' =>'h','group'=>'holidays','name'=>'holidays'],
        ];

        foreach ($absences as $absence) {
            DB::table('absences')->insert([
                'code' => $absence['code'],
                'group' => $absence['group'],
                'name' => $absence['name'],
            ]);
        }
    }
}
