<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
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
            'details.*.start_time'  => 'required|date_format:H:i',
            'details.*.finish_time' => 'required|date_format:H:i|after:details.*.start_time',
            'activities'            => 'required|array|min:1',
            'activities.*'          => 'required|string|distinct',
        ]);

        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $employee = $person->employee;
        if(!$employee) {
            return response(['message' => 'Registrese como empleado primero', 400]);
        }

        $staySchedule = StaySchedule::where([
            'id'            => $stayScheduleId, 
            'employee_id'  => $employee->id,
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

        foreach($fields['activities'] as $activityName) {
            $activity = Activity::where('name', 'ilike', $activityName)->first();
            if(!$activity){
                $activity = Activity::create(['name' => $activityName]);
            }
            $activityIds[] = $activity->id;
        }

        StayScheduleDetail::where('stay_schedule_id', $staySchedule->id)->delete();
        $scheduleDetails = $staySchedule->scheduleDetails()->createMany($fields['details']);
        $staySchedule->scheduleActivities()->sync($activityIds);

        $this->RegisterAction('El usuario ha registrado su horario de permanencia para el ciclo activo', 'medium');
        return response([
            'scheduleDetails'       => $scheduleDetails,
            'scheduleActivities'    => $staySchedule->scheduleActivities,
        ], 200);
    }
}
