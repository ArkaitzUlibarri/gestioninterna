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
            'username' => 'ivan.iglesias',
            'name'     => 'Ivan',
            'lastname' => 'Iglesias',
            'email'    => 'ivan.iglesias@3dbconsult.com',
            'role'     => 'tools',
        ]);
       
        factory(App\User::class)->create([
            'username' => 'arkaitz.ulibarri',
            'name'     => 'Arkaitz',
            'lastname' => 'Ulibarri',
            'email'    => 'arkaitz.ulibarri@3dbconsult.com',
            'role'     => 'tools',
        ]);

        factory(App\User::class)->create([
            'username' => 'paula.corral',
            'name'     => 'Paula',
            'lastname' => 'Corral',
            'email'    => 'paula.corral@3dbconsult.com',
            'role'     => 'admin',
        ]);

        factory(App\User::class)->create([
            'username' => 'argiñe.garcia',
            'name'     => 'Argiñe',
            'lastname' => 'Garcia',
            'email'    => 'argiñe.garcia@3dbconsult.com',
            'role'     => 'admin',
        ]);

        factory(App\User::class)->create([
            'username' => 'luis.sampedro',
            'name'     => 'Luis',
            'lastname' => 'Sampedro',
            'email'    => 'luis.sampedro@3dbconsult.com',
            'role'     => 'admin',
        ]);

        factory(App\User::class)->create([
            'username' => 'javier.vega',
            'name'     => 'Javier',
            'lastname' => 'Vega',
            'email'    => 'javier.vega@3dbconsult.com',
            'role'     => 'user',
        ]);

        factory(App\User::class)->create([
            'username' => 'borja.frias',
            'name'     => 'Borja',
            'lastname' => 'Frias',
            'email'    => 'borja.frias@3dbconsult.com',
            'role'     => 'user',
        ]);

        factory(App\User::class)->create([
            'username' => 'dorleta.sainz',
            'name'     => 'Dorleta',
            'lastname' => 'Sainz',
            'email'    => 'dorleta.sainz@3dbconsult.com',
            'role'     => 'user',
        ]);

        factory(App\User::class)->create([
            'username' => 'alvaro.gabilondo',
            'name'     => 'Alvaro',
            'lastname' => 'Gabilondo',
            'email'    => 'alvaro.gabilondo@3dbconsult.com',
            'role'     => 'user',
        ]);
    }
}
