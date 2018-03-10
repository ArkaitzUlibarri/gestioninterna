<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankHolidaysCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank_holidays_codes = [
            ['type' =>'national','name'=>'España','code'=>'ne'],
            ['type' =>'regional','name'=>'País vasco','code'=>'pv'],
            ['type' =>'regional','name'=>'Comunidad de Madrid','code'=>'cmad'],
            ['type' =>'regional','name'=>'Cataluña','code'=>'cat'],
            ['type' =>'local','name'=>'Bilbao','code'=>'bi'],
            ['type' =>'local','name'=>'Madrid','code'=>'mad'],
            ['type' =>'local','name'=>'Barcelona','code'=>'ba'],
            ['type' =>'others','name'=>'Adjustment','code'=>'adjustment'],
        ];

        /*
        $bank_holidays_codes = [
            ['type' =>'national','name'=>'Spain','code'=>'ne'],
            ['type' =>'regional','name'=>'Basque Country','code'=>'pv'],
            ['type' =>'regional','name'=>'Community of Madrid','code'=>'cmad'],
            ['type' =>'regional','name'=>'Catalonia','code'=>'cat'],
            ['type' =>'local','name'=>'Bilbao','code'=>'bi'],
            ['type' =>'local','name'=>'Madrid','code'=>'mad'],
            ['type' =>'local','name'=>'Barcelona','code'=>'ba'],
            ['type' =>'others','name'=>'adjustment','code'=>'adjustment'],
        ];
        */

        foreach ($bank_holidays_codes as $bank_holiday_code) {
            DB::table('bank_holidays_codes')->insert([
                'type' => $bank_holiday_code['type'],
                'name' => $bank_holiday_code['name'],
                'code' => $bank_holiday_code['code'],
            ]);
        }
    }
}
