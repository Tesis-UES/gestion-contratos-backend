<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractType;
class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractType::create(['name'=>'Contrato de Tiempo Adicional','description'=>'----']);
        ContractType::create(['name'=>'Contrato de Tiempo Integral','description'=>'']);
        ContractType::create(['name'=>'Contrato por Servicios Profesionales','description'=>'---']);
    }
}
