<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Escalafon;
use App\Models\Employee;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    use WorklogTrait;

    public function store(Request $request)
    {   
        $fields = $request->validate([
            'journey_type'      =>['required', Rule::in(['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo'])],
            'same_faculty'      => 'required|boolean',
            'escalafon_id'      => 'required|integer|gte:1',
            'employee_type_id'  => 'required|integer|gte:1',
        ]);
        
        Escalafon::findOrFail( $fields['escalafon_id']);
        EmployeeType::findOrFail($fields['employee_type_id']);

        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $employee = Employee::where('person_id', $person->id )->first();
        if($employee) { 
            return response(['message' => 'El usuario ya se registro como empleado'], 422);
        }

        $newEmployee = Employee::create([
            'journey_type'      => $fields['journey_type'],
            'same_faculty'      => $fields['same_faculty'],
            'person_id'         => $person->id,
            'escalafon_id'      => $fields['escalafon_id'],
            'employee_type_id'  => $fields['employee_type_id'],
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
