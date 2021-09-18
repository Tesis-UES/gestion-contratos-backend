<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Escalafon;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    use WorklogTrait;

    public function store(Request $request)
    {   
        $fields = $request->validate([
            'escalafon_id'     => 'required|integer|gte:1',
        ]);
        Escalafon::where('id', $fields['escalafon_id'])->firstOrFail();
        
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $employee = Employee::where('person_id', $person->id )->first();
        if($employee) { 
            return response(['message' => 'El usuario ya se registro como empleado'], 422);
        }

        $newEmployee = Employee::create([
            'person_id'     => $person->id,
            'escalafon_id'  => $fields['escalafon_id'],
        ]);

        $this->RegisterAction('El usuario se ha registrado como empleado', 'medium');
        return response($newEmployee, 201);
    }

    public function hasRegistered()
    {
        $employee = Auth::user()->person->employee;
        if($employee) {
            return response(['has_registered' => true], 200);
        }
        return response(['has_registered' => false], 200);
    }
}
