<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BankHolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank_holidays = [

            //2018
            ['date' => Carbon::createFromDate(2018, 1, 1),'code_id'=>1],//1 de Enero: Año nuevo
            ['date' => Carbon::createFromDate(2018, 1, 6),'code_id'=>1],//6 de Enero: Epifanía del Señor
            ['date' => Carbon::createFromDate(2018, 3, 19),'code_id'=>2],//19 de marzo - San José
            ['date' => Carbon::createFromDate(2018, 3, 29),'code_id'=>2],//29 de marzo - Jueves Santo
            ['date' => Carbon::createFromDate(2018, 3, 30),'code_id'=>1],//30 de marzo - Viernes Santo

            /**
             * Fiestas nacionales
             */
            ['date' => Carbon::createFromDate(null, 1, 6),'code_id'=>1],//6 de Enero: Epifanía del Señor
            ['date' => Carbon::createFromDate(2017, 4, 14),'code_id'=>1],//14 de Abril: Viernes Santo
            ['date' => Carbon::createFromDate(null, 5, 1),'code_id'=>1],//1 de Mayo: Fiesta del Trabajo
            ['date' => Carbon::createFromDate(null, 8, 15),'code_id'=>1],//15 de Agosto: Asunción de la Virgen
            ['date' => Carbon::createFromDate(null, 10, 12),'code_id'=>1],//12 de Octubre: Fiesta Nacional de España
            ['date' => Carbon::createFromDate(null, 11, 1),'code_id'=>1],//1 de Noviembre: Día de todos Los Santos
            ['date' => Carbon::createFromDate(null, 12, 6),'code_id'=>1],//6 de Diciembre: Día de la Constitución Española
            ['date' => Carbon::createFromDate(null, 12, 8),'code_id'=>1],//8 de Diciembre: Inmaculada Concepción
            ['date' => Carbon::createFromDate(null, 12, 25),'code_id'=>1],//25 de Diciembre: Natividad del Señor

            /**
             * Fiestas de las CCAA
             */
            ['date' => Carbon::createFromDate(2017, 4, 13),'code_id'=>2],//13 de Abril: Jueves Santo (en toda España excepto en Cataluña)
            ['date' => Carbon::createFromDate(2017, 4, 17),'code_id'=>2],//17 de Abril: Lunes de Pascua
            ['date' => Carbon::createFromDate(null, 7, 25),'code_id'=>2],//25 de Julio: Santiago Apostol

            ['date' => Carbon::createFromDate(2017, 3, 20),'code_id'=>3],//20 de Marzo: Lunes siguiente a San José
            ['date' => Carbon::createFromDate(2017, 4, 13),'code_id'=>3],//13 de Abril: Jueves Santo (en toda España excepto en Cataluña)
            ['date' => Carbon::createFromDate(null, 5, 2),'code_id'=>3],//2 de Mayo: Día de la Comunidad de Madrid

            ['date' => Carbon::createFromDate(2017, 4, 17),'code_id'=>4],//17 de Abril: Lunes de Pascua
            ['date' => Carbon::createFromDate(null, 6, 24),'code_id'=>4],//24 de Junio:San Juan
            ['date' => Carbon::createFromDate(null, 9, 11),'code_id'=>4],//11 de Septiembre:Fiesta Nacional de Cataluña
            ['date' => Carbon::createFromDate(null, 12, 6),'code_id'=>4],//26 de Diciembre: San Esteban

            /**
             * Fiestas de las Localidades
             */
            ['date' => Carbon::createFromDate(2017, 7, 31),'code_id'=>5],//31 de Julio:San Ignacio de Loyola
            ['date' => Carbon::createFromDate(2017, 8, 25),'code_id'=>5],//25 de Agosto:Viernes de la semana grande

            ['date' => Carbon::createFromDate(2017, 5, 15),'code_id'=>6],//15 de Mayo:San Isidro
            ['date' => Carbon::createFromDate(2017, 11, 9),'code_id'=>6],//9 de Noviembre:La Almudena

            ['date' => Carbon::createFromDate(2017, 6, 5),'code_id'=>7],//5 de Junio:Pascua Granada
            ['date' => Carbon::createFromDate(2017, 11, 25),'code_id'=>7],//9 de Noviembre:La Merced

            /**
             * Ajuste Calendario 3dB
             */
            ['date' => Carbon::createFromDate(2017, 10, 13),'code_id'=>8],//13 de Octubre
            ['date' => Carbon::createFromDate(2017, 12, 7),'code_id'=>8],//7 de Diciembre
        ];

        foreach ($bank_holidays as $bank_holiday) {
            DB::table('bank_holidays')->insert([
                'date' => $bank_holiday['date'],
                'code_id' => $bank_holiday['code_id'],
            ]);
        }
    }
}
