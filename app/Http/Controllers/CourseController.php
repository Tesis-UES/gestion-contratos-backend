<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\School;
use App\Http\Traits\WorklogTrait;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use WorklogTrait;
    public function all($id)
    {
        School::findOrFail($id);
        $courses = Course::where('school_id', $id)->with('studyPlan')->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de materias por Escuela");
        return response($courses, 200);
    }

    public function studyPlanCourses($id,$plan)
    {
        School::findOrFail($id);
        $courses = Course::where('school_id', $id)->where('study_plan_id',$plan)->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de materias por Escuela y plan de estudio");
        return response($courses, 200);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $fields = $request->validate([
            'name'          => 'required|string|max:200',
            'code'          => 'required|string|max:200',
            'study_plan_id' => 'required'
        ]);
        School::findOrFail($id);
        
        $course = Course::where('school_id', $id)->where('study_plan_id',$fields['study_plan_id'])->where('code', $fields['code'])->where('name','ILIKE','%'.$fields['name'].'%')->first();
        if ($course) {
            return response(['message' => 'Ya existe una materia con el mismo codigo y nombre en este plan de estudio'], 422);
        }else{
            $newCourse = Course::create([
                'school_id'     => $id,
                'study_plan_id' => $fields['study_plan_id'],
                'name'          => $fields['name'],
                'code'          => $fields['code'],
            ]);
            $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de materias por escuela", "medium");
            return response([
                'course' => $newCourse,
            ], 201);
        }

       
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findorFail($id);
        return response(['course' => $course], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:200',
            'code'          => 'required|string|max:200',
            'study_plan_id' => 'required',
        ]);

        $course = Course::findOrFail($id);
        $course->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro de " . $request['name'] . " en el catalogo de materias por escuela", "medium");
        return response(['course' => $course], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        $this->RegisterAction("El usuario ha eliminado el registro de la materia " . $course->name . " en el catalogo de Materias", "medium");
        return response(null, 204);
    }
}
