<?php

namespace Database\Seeders;

use App\Constants\ContractStatusCode;
use App\Models\ContractStatus;
use Illuminate\Database\Seeder;

class ContractStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractStatus::create(['code' => ContractStatusCode::ELB, 'name' => 'Elaborado', 'order' => '1']);
        ContractStatus::create(['code' => ContractStatusCode::RFS, 'name' => 'Revisión Fiscalía', 'order' => '2']);
        ContractStatus::create(['code' => ContractStatusCode::FCO, 'name' => 'Firma contratante', 'order' => '3']);
        ContractStatus::create(['code' => ContractStatusCode::FRE, 'name' => 'Firma Rectoría', 'order' => '4']);
        ContractStatus::create(['code' => ContractStatusCode::FIN, 'name' => 'Finalizado', 'order' => '5']);
    }
}
