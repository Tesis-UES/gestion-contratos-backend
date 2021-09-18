<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeType;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeType::create(['name'=>'Docente']);
        EmployeeType::create(['name'=>'Administrativo']);
        EmployeeType::create(['name'=>'Jefe de área']);
    }
}
