<?php

namespace Database\Seeders;

use App\Constants\HiringRequestStatusCode;
use App\Models\HiringRequest;
use App\Http\Traits\GeneratorTrait;
use Illuminate\Database\Seeder;
use App\Constants\ContractType;
use App\Constants\GroupStatus;
use App\Constants\PersonValidationStatus;
use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use App\Models\Group;
use App\Models\HiringGroup;
use App\Models\HiringRequestDetail;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;

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

        //candidato 1 - Servicios Profesionales No personales ANS
        $grupo1 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 8]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "6:00", 'finish_hour' => "8:05", 'group_id' => $grupo1->id], ['day' => 'Miercoles', 'start_hour' => "6:00", 'finish_hour' => "8:45", 'group_id' => $grupo1->id]);

        $grupo2 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 8]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "8:05", 'finish_hour' => "9:45", 'group_id' => $grupo2->id]);

        //Candidato 2 - Servicios Profesionales No personales BAD
        $grupo3 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 23]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo3->id], ['day' => 'Viernes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo3->id]);

        $grupo4 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 23]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo4->id]);

        $grupo5 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 23]);
        Schedule::create(['day' => 'Miercoles', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo5->id]);

        $grupo6 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 3, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 23]);
        Schedule::create(['day' => 'Sabado', 'start_hour' => "8:05", 'finish_hour' => "9:45", 'group_id' => $grupo6->id]);

        //Candidato 3 - Servicios Profesionales No personales DSI
        $grupo7 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 17]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo7->id], ['day' => 'Viernes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo7->id]);

        $grupo8 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 17]);
        Schedule::create(['day' => 'Sabado', 'start_hour' => "6:20", 'finish_hour' => "8:00", 'group_id' => $grupo8->id]);

        $grupo9 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 17]);
        Schedule::create(['day' => 'Sabado', 'start_hour' => "8:05", 'finish_hour' => "9:45", 'group_id' => $grupo9->id]);

        //materias para tiempo Adicional  MIP
        $grupo10 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 15]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo10->id], ['day' => 'Viernes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo10->id]);

        $grupo11 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 15]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo11->id]);

        $grupo12 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 15]);
        Schedule::create(['day' => 'Sabado', 'start_hour' => "6:20", 'finish_hour' => "8:00", 'group_id' => $grupo12->id]);

        $grupo13 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 3, 'group_type_id' => 2, 'academic_load_id' => 6, 'course_id' => 15]);
        Schedule::create(['day' => 'Sabado', 'start_hour' => "8:05", 'finish_hour' => "9:45", 'group_id' => $grupo13->id]);

        //materias para tiempo Adicional  PRN2 
        $grupo14 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo14->id], ['day' => 'Miercoles', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo14->id]);

        $grupo15 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo15->id]);

        $grupo16 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo16->id]);
        //RHU
        $grupo17 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 22]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo17->id], ['day' => 'Miercoles', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo17->id]);

        $grupo18 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "6:20", 'finish_hour' => "8:00", 'group_id' => $grupo18->id]);

        $grupo19 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "8:05", 'finish_hour' => "9:45", 'group_id' => $grupo19->id]);

        $grupo20 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 3, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 3]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "15:05", 'finish_hour' => "16:45", 'group_id' => $grupo20->id]);

        //SGG
        $grupo21 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 38]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo21->id], ['day' => 'Miercoles', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo21->id]);

        $grupo22 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 38]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo22->id]);

        $grupo23 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 38]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "16:50", 'finish_hour' => "18:30", 'group_id' => $grupo23->id]);

        //SIF 
        $grupo24 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 33]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo24->id], ['day' => 'Miercoles', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo24->id]);

        $grupo25 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 33]);
        Schedule::create(['day' => 'Jueves', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo25->id]);

        $grupo26 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 33]);
        Schedule::create(['day' => 'Viernes', 'start_hour' => "9:50", 'finish_hour' => "11:30", 'group_id' => $grupo25->id]);

        //SYP
        $grupo27 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 1, 'academic_load_id' => 6, 'course_id' => 10]);
        Schedule::create(['day' => 'Lunes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo27->id], ['day' => 'Miercoles', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo27->id]);

        $grupo28 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 1, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 10]);
        Schedule::create(['day' => 'Martes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo25->id]);

        $grupo29 = Group::create(['status' => 'SDA', 'modality' => 'Presencial', 'number' => 2, 'group_type_id' => 3, 'academic_load_id' => 6, 'course_id' => 10]);
        Schedule::create(['day' => 'Viernes', 'start_hour' => "18:35", 'finish_hour' => "20:15", 'group_id' => $grupo25->id]);
        //-----------------------SOLICITUDES--------------------------------//
        //solicitud por Servicios Profesionales no personles
        $rq =  HiringRequest::create([
            'code' => $this->generateRequestCode(8),
            'contract_type_id' => 1,
            'school_id' => 8,
            'modality' => 'Modalidad Presencial',
            'message' => '<p>Estimados se&ntilde;ores:</p> <p>Les saludo deseando que gocen de &eacute;xitos personales y profesionales.</p> <p>Por este medio amablemente les solicito contratar al personal que se especifica a continuaci&oacute;n,<br />para realizar las funciones especificadas en los archivos anexos.</p>',
            'request_status' => HiringRequestStatusCode::RDC,
        ]);
        $rq->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
        $rq->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);

        $personas = [1, 2, 5];
        foreach ($personas as $p) {
            $savedDetail = HiringRequestDetail::create([
                'hiring_request_id' =>  $rq->id,
                'start_date'        => '2022-01-17',
                'finish_date'       => '2022-06-17',
                'position'          => 'Dar Clases de las materias analisis numerico, progrmación 2 e impartir laboratorios de las materias antes mencionadas.',
                'person_id'         => $p
            ]);

            $act = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
            
            foreach ($act as $activityName) {
                $activity = Activity::where('id', $activityName)->first();
                $activities[] = $activity;
            }
            $savedDetail->activities()->saveMany($activities);
            if ($p == 1) {
                $groups = [$grupo1->id, $grupo2->id];
            } elseif ($p == 2) {
                $groups = [$grupo3->id, $grupo4->id, $grupo5->id, $grupo6->id];
            } else {
                $groups = [$grupo7->id, $grupo8->id, $grupo9->id];
            }
            foreach ($groups as $hiringGroup) {
                $group = Group::findOrFail($hiringGroup);
                $group->people_id = $p;
                $group->status = GroupStatus::DASC;
                $group->save();
                if ($group->group_type_id == 1) {
                    $wh = 4;
                } else {
                    $wh = 2;
                }
                $savedHiringGroup = HiringGroup::create([
                    'hiring_request_detail_id' => $savedDetail->id,
                    'group_id' => $group->id,
                    'hourly_rate' => 10,
                    'work_weeks' => 16,
                    'weekly_hours' => $wh,
                ]);
            }
            $activities = [];
            $groups =[] ;
        }

          //solicitud por Tiempo Integral
        $rqi =  HiringRequest::create([
            'code' => $this->generateRequestCode(8),
            'contract_type_id' => 2,
            'school_id' => 8,
            'modality' => 'Modalidad Presencial',
            'message' => '<p>Estimados se&ntilde;ores:</p> <p>Les saludo deseando que gocen de &eacute;xitos personales y profesionales.</p> <p>Por este medio amablemente les solicito contratar al personal que se especifica a continuaci&oacute;n,<br />para realizar las funciones especificadas en los archivos anexos.</p>',
            'request_status' => HiringRequestStatusCode::RDC,
        ]);
        $rqi->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
        $rqi->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);
        $pers= [3, 4];

        foreach ($pers as $p) {
            if($p == 3){
                $stay = 1;
            }else{
                $stay = 2;}
            $savedDetail = HiringRequestDetail::create([
                'start_date'        => '2022-01-17',
                'finish_date'       => '2022-05-17',
                'stay_schedule_id' =>$stay,
                'position'          => 'Dar Clases asignadas y laboratorios de las materias antes mencionadas.',
                'goal'              => 'Atender a los estudiantes en el ciclo 1-2022',
                'justification'     => 'Falta de personal para atender a los estudiantes en el ciclo 1-2022',
                'work_months'       => 4,
                'monthly_salary'    => 1892,
                'salary_percentage' => 0.25,
                'person_id'         => $p,
                'hiring_request_id' => $rqi->id]);

                $act = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
                
                foreach ($act as $activityName) {
                    $activity = Activity::where('id', $activityName)->first();
                    $activities[] = $activity;
                }
                $savedDetail->activities()->saveMany($activities);
                
                if ($p == 3) {
                    $groups = [$grupo7->id, $grupo8->id, $grupo5->id, $grupo9->id];
                } else{
                    $groups = [$grupo10->id, $grupo11->id, $grupo12->id, $grupo13->id];
                } 

                foreach ($groups as $hiringGroup) {
                    $group = Group::findOrFail($hiringGroup);
                    $group->people_id = $p;
                    $group->status = GroupStatus::DASC;
                    $group->save();
                    $gps[] = $group;
                }
                $savedDetail->groups()->saveMany($gps);
                $activities = [];
                $gps = [];
                
        } 
    
    }
}
