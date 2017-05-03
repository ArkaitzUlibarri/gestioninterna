<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name'       => 'Ivan',
            'lastname_1' => 'Iglesias',
            'email'      => 'ivan.iglesias@3dbconsult.com',
            'role'       => 'tools',
            //'category'   => 'ds'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Arkaitz',
            'lastname_1' => 'Ulibarri',
            'email'      => 'arkaitz.ulibarri@3dbconsult.com',
            'role'       => 'tools',
            //'category'   => 'ds'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Paula',
            'lastname_1' => 'Corral',
            'email'      => 'paula.corral@3dbconsult.com',
            'role'       => 'admin',
            //'category'   => 'ad'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Argiñe',
            'lastname_1' => 'Garcia',
            'email'      => 'argiñe.garcia@3dbconsult.com',
            'role'       => 'admin',
            //'category'   => 'ad'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Luis',
            'lastname_1' => 'Sampedro',
            'email'      => 'luis.sampedro@3dbconsult.com',
            'role'       => 'admin',
            //'category'   => 'di'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Javier',
            'lastname_1' => 'Vega',
            'email'      => 'javier.vega@3dbconsult.com',
            'role'       => 'user',
            //'category'   => 'rp'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Borja',
            'lastname_1' => 'Frias',
            'email'      => 'borja.frias@3dbconsult.com',
            'role'       => 'user',
            //'category'   => 'it'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Dorleta',
            'lastname_1' => 'Sainz',
            'email'      => 'dorleta.sainz@3dbconsult.com',
            'role'       => 'user',
            //'category'   => 'it'
        ]);

        factory(App\User::class)->create([
            'name'       => 'Alvaro',
            'lastname_1' => 'Gabilondo',
            'email'      => 'alvaro.gabilondo@3dbconsult.com',
            'role'       => 'user',
            //'category'   => 'it'
        ]);
    }
}
