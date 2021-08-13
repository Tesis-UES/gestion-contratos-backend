<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupType;

class GroupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupType::create(['name'=>'Teorico']);
        GroupType::create(['name'=>'Laboratorio']);
        GroupType::create(['name'=>'Discusión']);
        GroupType::create(['name'=>'Taller']);
        GroupType::create(['name'=>'Foro']);
        GroupType::create(['name'=>'Critica']);
    }
}
