<?php

namespace Database\Seeders;

use App\Constants\HiringRequestStatusCode;
use App\Models\HiringRequest;
use App\Http\Traits\GeneratorTrait;
use Illuminate\Database\Seeder;

class HrSeeder extends Seeder
{
    use GeneratorTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Script que generara 3 hiring requests que se manejan el sistema de manera manual
        //Primero Agregamos las materias que se utilizaran en las solicitudes

        //-----------------------MATERIIAS----------------------------------//

        //candidato 1 - Servicios Profesionales No personales
        $grupo1 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>8]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"6:00",'finish_hour'=>"8:05",'group_id'=>$grupo1->id]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"6:00",'finish_hour'=>"8:45",'group_id'=>$grupo1->id]);

        $grupo2 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>8]);
        Schedule::create(['day'=>'Lunes','start_hour'=>"8:05",'finish_hour'=>"9:45",'group_id'=>$grupo2->id]);

        //Candidato 2 - Servicios Profesionales No personales
        $grupo3 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Martes','start_hour'=>"16:50",'finish_hour'=>"18:30",'group_id'=>$grupo3->id]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"16:50",'finish_hour'=>"18:30",'group_id'=>$grupo3->id]);

        $grupo4 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Martes','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo4->id]);

        $grupo5 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Miercoles','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo5->id]);

        $grupo6 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>3,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>23]);
        Schedule::create(['day'=>'Sabado','start_hour'=>"8:05",'finish_hour'=>"9:45",'group_id'=>$grupo6->id]);

        //Candidato 3 - Servicios Profesionales No personales
        $grupo7 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>17]);
        Schedule::create(['day'=>'Martes','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo7->id]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo7->id]);

        $grupo8 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>3,'academic_load_id'=>6,'course_id'=>17]);
        Schedule::create(['day'=>'Sabado','start_hour'=>"6:20",'finish_hour'=>"8:00",'group_id'=>$grupo8->id]);

        $grupo9 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>17]);
        Schedule::create(['day'=>'Sabado','start_hour'=>"8:05",'finish_hour'=>"9:45",'group_id'=>$grupo9->id]);

        //materias para tiempo integral 
        $grupo10 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>1,'academic_load_id'=>6,'course_id'=>15]);
        Schedule::create(['day'=>'Martes','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo10->id]);
        Schedule::create(['day'=>'Viernes','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo10->id]);

        $grupo11 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>1,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>15]);
        Schedule::create(['day'=>'Jueves','start_hour'=>"18:35",'finish_hour'=>"20:15",'group_id'=>$grupo11->id]);

        $grupo12 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>2,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>15]);
        Schedule::create(['day'=>'Sabado','start_hour'=>"6:20",'finish_hour'=>"8:00",'group_id'=>$grupo12->id]);

        $grupo13 = Group::create(['status'=>'SDA','modality'=>'Presencial','number'=>3,'group_type_id'=>2,'academic_load_id'=>6,'course_id'=>15]);
        Schedule::create(['day'=>'Sabado','start_hour'=>"8:05",'finish_hour'=>"9:45",'group_id'=>$grupo12->id]);


    
        //-----------------------SOLICITUDES--------------------------------//
            $faker = \Faker\Factory::create();
            $rq =  HiringRequest::create([
                'code' => $this->generateRequestCode($schoolId),
                'contract_type_id' => 1,
                'school_id' => 8,
                'modality' => 'Modalidad Presencial',
                'message' => $faker->text,
                'request_status' => HiringRequestStatusCode::RDC,
            ]);
            $rq->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
            $rq->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);
        

        
    }
}
