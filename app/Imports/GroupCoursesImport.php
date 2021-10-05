<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\{Group,Schedule,GroupType,Course};
class GroupCoursesImport implements ToCollection, WithHeadingRow
{


    public function  __construct($academicLoadId)
    {
        $this->al_id = $academicLoadId;
    }

    public function collection(Collection $rows)
    {
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
           
            //Creamos el Grupo
            $GroupRegister = Group::create([
            'number'                => $group ,
            'group_type_id'         => $groupType->id,
            'course_id'             => $course->id,
            'academic_load_id'      => $academicLoad,
            ]);
            $GroupRegister->schedule()->createMany($details);
            
        }
    }
}
