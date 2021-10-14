<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Semester;
use App\Models\StaySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StayScheduleController extends Controller
{
    use WorklogTrait;


    public function allMine()
    {
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }

        $employee =$person->employee;
        if(!$employee) {
            return response(['message' => 'Registrese como empleado primero'], 400);
        }

        $staySchedules = StaySchedule::where('employee_id', $employee->id)->with('semester')->get();
        
        $this->RegisterAction('El empleado ha consultado el catalogo de sus horarios de permanencia');
        return response($staySchedules, 200);
    }

    public function registerForActiveSemester()
    {
        $semester = Semester::firstWhere('status',1);
        if(!$semester) {
            return response(['message' => 'No existe ciclo activo, comuniquese con el administrador del sistema'], 400);
        }
        
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }

        $employee = $person->employee;
        if(!$employee) {
            return response(['message' => 'Registrese como empleado primero'], 400);
        }

        $existingStaySchedule = StaySchedule::firstWhere([
            'semester_id'   => $semester->id,
            'employee_id'  => $employee->id,
        ]);
        if($existingStaySchedule) {
            return response(['message' => 'Ya existe  un horario de permanencia para el ciclo activo'], 400);            
        }

        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employee->id,
        ]);
        $this->RegisterAction('El empleado ha consultado el catalogo de sus horarios de permanencia', 'medium');
        return response($newStaySchedule, 200);
    }

    public function show($id)
    {
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }

        $employee = $person->employee;
        if(!$employee) {
            return response(['message' => 'Registrese como empleado primero'], 400);
        }

        $staySchedule = StaySchedule::where([
            'id'            => $id, 
            'employee_id'  => $employee->id,
            ])
        ->with(['semester', 'scheduleDetails', 'scheduleActivities.activity'])
        ->firstOrFail();

        $this->RegisterAction('El empleado ha consultado los detalles de su horario de permanencia con id: '.$id);
        return response($staySchedule, 200);
    }
}
