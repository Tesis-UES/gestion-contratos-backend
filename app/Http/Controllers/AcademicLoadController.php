<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class AcademicLoadController extends Controller
{
   
    use WorklogTrait;
    
    public function store(Request $request)
    {
        $fields = $request->validate([
            'course_id'     => 'required',
            'semester_id'   => 'required',
        ]);

        $result = AcademicLoad::where(['course_id'=>$request->course_id,'semester_id'=>$request->semester_id])->first();
        if ($result == null) {
            $newAcademicLoad = AcademicLoad::create($request->all());
            $this->RegisterAction("El usuario ha registrado una nueva carga acacdemica para la materia ".$newAcademicLoad->course->name."");
             return response([
                'academicLoad' => $newAcademicLoad,
               ], 201);
        } else {
            return response([
                'message' => "No se puede Registra carga academica de esta materia por que ya tiene una activa en el ciclo.",
            ], 422);
        }
        
    }

    
    public function show($id)
    {
        $result = AcademicLoad::findOrFail($id);
        $academicLoad = [
            'id'            => $result->id,
            'semester_id'   => $result->semester_id,
            'course_id'     => $result->course_id,
            'semesterName'  => $result->semester->name,
            'start_date'    => $result->semester->start_date,
            'end_date'      => $result->semester->end_date,
            'courseName'    => $result->course->name,
            'courseCode'    => $result->course->code,
        ];
        return response(['academicLoad' =>   $academicLoad,], 200);
    }

    
    
    public function update(Request $request, AcademicLoad $academicLoad)
    {
        //
    }

    
    public function destroy(AcademicLoad $academicLoad)
    {
        //
    }
}
