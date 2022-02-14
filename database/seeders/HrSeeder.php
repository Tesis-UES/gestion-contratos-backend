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

       
        //-----------------------SOLICITUDES--------------------------------//
        //solicitud por Servicios Profesionales no personles
        $rq =  HiringRequest::create([
            'code' => $this->generateRequestCode(8),
            'contract_type_id' => 3,
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
            $act = [];
            $act = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
            
            foreach ($act as $activityName) {
                $activity = Activity::where('id', $activityName)->first();
                $activities[] = $activity;
            }
            $savedDetail->activities()->saveMany($activities);
            if ($p == 1) {
                $groups = [55, 56];
            } elseif ($p == 2) {
                $groups = [57,58,59, 60];
            } else {
                $groups = [61,62, 63];
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
                    $groups = [68, 69, 70, 71];
                } else{
                    $groups = [64,65,66,67];
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

          //solicitud por Tiempo Adicional
          $rqi =  HiringRequest::create([
            'code' => $this->generateRequestCode(8),
            'contract_type_id' => 1,
            'school_id' => 8,
            'modality' => 'Modalidad Presencial',
            'message' => '<p>Estimados se&ntilde;ores:</p> <p>Les saludo deseando que gocen de &eacute;xitos personales y profesionales.</p> <p>Por este medio amablemente les solicito contratar al personal que se especifica a continuaci&oacute;n,<br />para realizar las funciones especificadas en los archivos anexos.</p>',
            'request_status' => HiringRequestStatusCode::RDC,
        ]);
        $rqi->status()->attach(['status_id' => '1'], ['comments' => 'Registro de solicitud']);
        $rqi->status()->attach(['status_id' => '2'], ['comments' => 'Llenado de datos de solicitud de contratación']);
        $perso= [6, 7];

        foreach ($perso as $p) {
            if($p == 6){
                $stay = 3;
            }else{
                $stay = 4;}
            $savedDetail = HiringRequestDetail::create([
                'start_date'        => '2022-01-17',
                'finish_date'       => '2022-06-1',
                'stay_schedule_id' =>$stay,
                'weekly_hours'      => 8,
                'work_weeks'        => 20,
                'hourly_rate'       => 10,
                'position'         => 'Dar Clases asignadas y laboratorios de las materias antes mencionadas.',
                'person_id'         => $p,
                'hiring_request_id' => $rqi->id]);

                $act = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
                
                foreach ($act as $activityName) {
                    $activity = Activity::where('id', $activityName)->first();
                    $activities[] = $activity;
                }
                $savedDetail->activities()->saveMany($activities);
                
                if ($p == 3) {
                    $groups = [81, 82, 83];
                } else{
                    $groups = [75,76,77];
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
