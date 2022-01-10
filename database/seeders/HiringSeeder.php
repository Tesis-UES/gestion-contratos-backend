<?php

namespace Database\Seeders;

use App\Models\HiringRequest;
use App\Http\Traits\GeneratorTrait;
use Illuminate\Database\Seeder;

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
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $hrQty; $i++) {
            $schoolId = mt_rand(1, 9);
            $rq =  HiringRequest::create([
                'code' => $this->generateRequestCode($schoolId),
                'contract_type_id' => mt_rand(1, 3),
                'school_id' => $schoolId,
                'modality' => 'Modalidad Presencial',
                'message' => $faker->text,
            ]);
            $rq->status()->attach(['status_id' => '1'], ['comments' => 'Creado']);
        }
    }
}
