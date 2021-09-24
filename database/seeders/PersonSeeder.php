<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Person};

class PersonSeeder extends Seeder
{
    
    public function run()
    {
        //Creamo dos Usuarios para las pruebas de Candidato Nacional / Internacional
        $Nacional = User::create([
            'name'      => 'Usuario Nacional',
            'email'     => 'nacional@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $Nacional->assignRole('Candidato');

        $Internacional = User::create([
            'name'      => 'Usuario Extranjero',
            'email'     => 'extranjero@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $Internacional->assignRole('Candidato');
    }
}
