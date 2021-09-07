<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\StaySchedule;
use App\Models\StayScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StayScheduleDetailController extends Controller
{
    use WorklogTrait;

    public function store(Request $request, $stayScheduleId)
    {
        $fields = $request->validate([
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_time'  => 'required|date_format:H:i:s',
            'details.*.finish_time' => 'required|date_format:H:i:s|after:details.*.start_time',
        ]);
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $professor = $person->professor;
        if(!$professor) {
            return response(['message' => 'Registrese como profesor primero', 400]);
        }

        $staySchedule = StaySchedule::where([
            'id'            => $stayScheduleId, 
            'professor_id'  => $professor->id,
        ])->with('semester')->firstOrFail();
        if($staySchedule->semester->status = false){
            return response(['message' => 'No se puede editar horario de permanencia de ciclos inactivos'], 422);
        }

        foreach($fields['details'] as $detail) {
            $conflictingDetails = array_filter($fields['details'], function($i) use($detail){
                return $detail['day'] == $i['day'] 
                && strtotime($detail['finish_time']) > strtotime($i['start_time'])
                && strtotime($detail['start_time']) < strtotime($i['finish_time']);
            });

            if(count($conflictingDetails) > 1) {
                return response(['message' => 'Hay conflictos en los horarios seleccionados'], 422);
            }
        }

        StayScheduleDetail::where('stay_schedule_id', $staySchedule->id)->delete();
        $savedDetails =$staySchedule->details()->createMany($fields['details']);
        
        $this->RegisterAction('El usuario ha registrado su horario de permanencia para el ciclo activo', 'medium');
        return response($savedDetails, 200);
    }
}
