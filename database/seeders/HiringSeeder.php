<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HiringRequest;
use App\Http\Traits\GeneratorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
class HiringSeeder extends Seeder
{
    use GeneratorTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
         $hrQty = 1000;
         
        for ($i=0; $i < $hrQty ; $i++) { 
            $faker = \Faker\Factory::create();
            HiringRequest::create([
                'code' =>$this->generateRequestCode(mt_rand(1,9)), 
                'contract_type_id' => mt_rand(1,3), 
                'school_id' =>mt_rand(1,9), 
                'modality' => 'Modalidad Presencial', 
                'message' => $faker->text, 
            ]);
        }
        
      
    }
}
