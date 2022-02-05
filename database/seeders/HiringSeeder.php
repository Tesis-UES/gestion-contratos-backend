<?php

namespace Database\Seeders;

use App\Constants\HiringRequestStatusCode;
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
        $hrQty = 25;
        $hrQrySec = 25;
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $hrQty; $i++) {
            $schoolId = mt_rand(1, 9);
            $rq =  HiringRequest::create([
                'code' => $this->generateRequestCode($schoolId),
                'contract_type_id' => mt_rand(1, 3),
                'school_id' => $schoolId,
                'modality' => 'Modalidad Presencial',
                'message' => $faker->text,
                'request_status' => HiringRequestStatusCode::RDC,
            ]);
            $rq->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
            $rq->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);
        }

        for ($i = 0; $i < $hrQrySec; $i++) {
            $schoolId = mt_rand(1, 9);
            $rq =  HiringRequest::create([
                'code' => $this->generateRequestCode($schoolId),
                'contract_type_id' => mt_rand(1, 3),
                'school_id' => $schoolId,
                'modality' => 'Modalidad Presencial',
                'message' => $faker->text,
                'request_status' => HiringRequestStatusCode::EDS,
            ]);
            $rq->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
            $rq->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud']);
            $rq->status()->attach(['status_id' => '3'], ['comments' => 'Finalizacion del llenado de la solicitud']);
            $rq->status()->attach(['status_id' => '4'], ['comments' => 'Solicitud enviada a Secretaria de Decanato']);
        }
    }
}
