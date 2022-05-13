<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Escalafon;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    use WorklogTrait;

    public function store(Request $request)
    {
        $fields = $request->validate([
            'partida'           => 'string|max:50',
            'sub_partida'       => 'string|max:50',
            'journey_type'      => ['required', Rule::in(['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo', 'tiempo-parcial', 'tiempo-eventual'])],
            'faculty_id'        => 'required|integer|gte:1',
            'escalafon_id'      => 'required|integer|gte:1',
            'employee_types'    => 'required|array|min:1',
            'employee_types.*'  => 'required|integer|distinct|gte:1',
        ]);

        Escalafon::findOrFail($fields['escalafon_id']);
        Faculty::findOrFail($fields['faculty_id']);
        foreach ($fields['employee_types'] as $typeId) {
            $employeeTypes[] = EmployeeType::findOrFail($typeId);
        }

        $person = Auth::user()->person;
        if (!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }

        $employee = Employee::where('person_id', $person->id)->first();
        if ($employee) {
            return response(['message' => 'El usuario ya se registro como empleado'], 422);
        }

        $newEmployee = Employee::create(array_merge($fields, ['person_id' => $person->id]));
        $newEmployee->employeeTypes()->saveMany($employeeTypes);

        $this->RegisterAction('El usuario se ha registrado como empleado', 'medium');
        return response($newEmployee, 201);
    }

    public function listEmployees()
    {
        $select = [
            'employees.id',
            'people.first_name',
            'people.middle_name',
            'people.last_name',
            'employees.partida',
            'employees.sub_partida',
        ];
        $employees = Employee::leftJoin('people', 'employees.person_id', '=', 'people.id')
            ->select($select)
            ->orderBy('people.first_name')
            ->get();

        $this->RegisterAction('El usuario se ha consultado el listado de empleados', 'medium');
        return $employees;
    }

    public function hasRegistered()
    {
        
        if (Auth::user()->person == null){
            return response(['has_registered' => false], 200);
        }else{
            $employee = Auth::user()->person->employee;
            if ($employee) {
                return response(['has_registered' => true], 200);
            }else{
                return response(['has_registered' => false], 200);
            }
        }
        
       
    }
}
