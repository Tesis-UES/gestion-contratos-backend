<?php

namespace Database\Seeders;

use App\Constants\ContractType as ConstantsContractType;
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
        ContractType::create(['name' => ConstantsContractType::TA, 'description' => '---']);
        ContractType::create(['name' => ConstantsContractType::TI, 'description' => '---']);
        ContractType::create(['name' => ConstantsContractType::SPNP, 'description' => '---']);
    }
}
