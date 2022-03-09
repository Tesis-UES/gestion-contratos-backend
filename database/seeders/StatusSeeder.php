<?php

namespace Database\Seeders;

use App\Constants\HiringRequestStatusCode;
use Illuminate\Database\Seeder;
use App\Models\Status;


class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create(['code' => HiringRequestStatusCode::CSC, 'name' => 'Creación de solicitud de contratación.', 'description' => 'Se ha registrado la creación de una solicitud de contratación por parte del director de escuela y se estan llenando los datos correspondientes a la solicitud.', 'order' => '1']);
        Status::create(['code' => HiringRequestStatusCode::RDC, 'name' => 'Registro de candidatos en la solicitud', 'description' => 'Se estan ingresando los detalles de los candidatos a contratar en la solicitud', 'order' => '2']);
        Status::create(['code' => HiringRequestStatusCode::FSC, 'name' => 'Finalización de solicitud de contratación', 'description' => 'Se ha terminado de registrar los candidatos en la solicitud de contratacion', 'order' => '3']);
        Status::create(['code' => HiringRequestStatusCode::EDS, 'name' => 'Envio de solicitud de contratación a Secretaria de Decanato.', 'description' => 'Se ha enviado la solicitud de contratacion por parte de la escuela a decanato, Pendiente de dar por recibido.', 'order' => '4']);
        Status::create(['code' => HiringRequestStatusCode::RDS, 'name' => 'Recepción solicitud de contratación por parte de Secretaria de Decanato.', 'description' => 'Se ha recibido la solicitud de contratacion por parte de secretaria de decanato y se ha agendado para discusión de Junta Directiva', 'order' => '5']);
        Status::create(['code' => HiringRequestStatusCode::RJD, 'name' => 'Resolución de solicitud de contratación por parte de Junta Directiva', 'description' => 'Se ha aprobado la solicitud de contratacion y se ha subido su correspondiente acuerdo de Junta directiva', 'order' => '6']);
        Status::create(['code' => HiringRequestStatusCode::GDC, 'name' => 'Generación de Contratos', 'description' => 'Se estan generado los contratos aprobados por Junta Directiva.', 'order' => '7']);
    }
}
