<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [
            ['name'=>'3db'],
            ['name'=>'alcatel'],
            ['name'=>'berrio-otxoa ikastetxea'],
            ['name'=>'elecnor'],
            ['name'=>'elisa'],
            ['name'=>'ems2'],
            ['name'=>'ericsson'],
            ['name'=>'euskaltel'],
            ['name'=>'everis'],
            ['name'=>'gobierno vasco'],
            ['name'=>'mercedes-benz'],
            ['name'=>'varios'],
            ['name'=>'yoigo'],
        ];

        foreach ($customers as $customer) {
            DB::table('customers')->insert([
                'name' => $customer['name'],
            ]);
        }
    }
}
