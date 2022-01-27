<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\{Group,Schedule,GroupType,Course};
class GroupCoursesImport implements ToCollection, WithHeadingRow
{
    public $resultM;
    public $resultB;

    public function  __construct($academicLoadId)
    {
        $this->al_id = $academicLoadId;
    }

    public function collection(Collection $rows)
    {  $errorGroups=[];
       $successGroups=[];
        foreach ($rows as $row) {
            
            //Primero Obtenemos los Datos relacionados consultados desde otras tablas
            $course = Course::where('code','=',$row['materia'])->get()->first();
            $groupType = GroupType::where('name','=',$row['tipo'])->get()->first();
            $group = $row['grupo'];
            $academicLoad = $this->al_id;
            // Ahora preramos el array que contendra el horario
            $details = [];
            
            if ($row['lunes']!='x') {
                $horario = explode("-",$row['lunes']);
                $hr = [
                    'day'         => 'Lunes',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['martes']!='x') {
                $horario = explode("-",$row['martes']);
                $hr = [
                    'day'         => 'Martes',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['miercoles']!='x') {
                $horario = explode("-",$row['miercoles']);
                $hr = [
                    'day'         => 'Miercoles',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['jueves']!='x') {
                $horario = explode("-",$row['jueves']);
                $hr = [
                    'day'         => 'Jueves',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['viernes']!='x') {
                $horario = explode("-",$row['viernes']);
                $hr = [
                    'day'         => 'Viernes',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['sabado']!='x') {
                $horario = explode("-",$row['sabado']);
                $hr = [
                    'day'         => 'Sabado',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }

            if ($row['domingo']!='x') {
                $horario = explode("-",$row['domingo']);
                $hr = [
                    'day'         => 'Domingo',
                    'start_hour'  =>$horario[0],
                    'finish_hour' =>$horario[1],
                ];
                array_push($details, $hr);
            }
            $errorDetail = 0;
            foreach ($details as $det) {
                if (strtotime($det['start_hour']) > strtotime($det['finish_hour'])) {
                    $errorDetail++;
                } 
             }
            
            //Validamos que no exista ningun grupo duplicado y discrepacia de horarios
           
                    $grupo = [
                        'materia'              => $row['materia'],
                        'tipo_de_grupo'        =>$row['tipo'],
                        'numero_grupo'         =>$group,
                        'horarios'             =>$details,   
                        'modalidad'            =>$row['modalidad'],
                        'Error'=>''        
                    ];
                    $os = array("Presencial","En Linea");
                 if ($course == null || $groupType == null ||in_array($row['modalidad'], $os) == false) {
                    if ($course == null) {
                        $grupo['Error' ] = 'El codigo de la materia ingresado No existe';               
                    } elseif($groupType == null){
                        $grupo['Error' ] = 'El tipo de Grupo ingresado  No existe';
                    }else{
                        $grupo['Error' ] = 'La modalidad ingresada no es valida';
                    }
                    
                    array_push($errorGroups, $grupo);
                 } else {
                    $result = Group::where([
                        'number'                => $group ,
                        'group_type_id'         => $groupType->id,
                        'course_id'             => $course->id,
                        'academic_load_id'      => $academicLoad,
                        'modality'              =>$row['modalidad']])->get();
                    if ($result->isEmpty()&&$errorDetail == 0) {           
                        //Creamos el Grupo
                            $GroupRegister = Group::create([
                                'number'                => $group ,
                                'group_type_id'         => $groupType->id,
                                'course_id'             => $course->id,
                                'academic_load_id'      => $academicLoad,
                                'status'                => 'SDA',
                                'modality'              => $row['modalidad'],
                                ]);
                            $GroupRegister->schedule()->createMany($details);
                            $grupoB = [
                                'materia'              => $row['materia'],
                                'tipo_de_grupo'        =>$row['tipo'],
                                'numero_grupo'         =>$group,
                                'horarios'             =>$details           
                            ];
                        array_push($successGroups, $grupoB);
                }else{
                   if ($errorDetail > 0) {
                    $grupo['Error' ] = 'Las horas de este grupo son incongruentes';
                    array_push( $grupo,$var);
                   } else {
                    $grupo['Error' ] = "Ya existe un grupo en la modalidad  ".$row['modalidad']." del tipo de grupo ".$row['tipo']." de esta materia resgistrado con el numero de ".$row['tipo']." ".$group."";
                    
                   }
                   array_push($errorGroups, $grupo);
                }  
                 }
                 
                
             
        }
        $this->resultM =  $errorGroups;
        $this->resultB =  $successGroups;
    }
}
