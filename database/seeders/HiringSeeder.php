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
        $hrQty = 100;
        $hrQrySec = 100;        
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
            $rq->status()->attach(['status_id' => '2'], ['comments' => 'LLenado de datos de solicitud']);
        }

        for ($i = 0; $i < $hrQrySec; $i++) {
            $schoolId = mt_rand(1, 9);
            $rq =  HiringRequest::create([
                'code' => $this->generateRequestCode($schoolId),
                'contract_type_id' => mt_rand(1, 3),
                'school_id' => $schoolId,
                'modality' => 'Modalidad Presencial',
                'message' => $faker->text,
            ]);
            $rq->status()->attach(['status_id' => '1'], ['comments' => 'Creado']);
            $rq->status()->attach(['status_id' => '2'], ['comments' => 'LLenado de datos de solicitud']);
            $rq->status()->attach(['status_id' => '3'], ['comments' => 'Finalizacion del llenado de la solicitud']);
            $rq->status()->attach(['status_id' => '4'], ['comments' => 'SOlicitud enviada a Secretaria de Decanato']);
        }
    }
}
