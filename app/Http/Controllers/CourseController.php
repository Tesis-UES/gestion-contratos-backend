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
        $courses = Course::where('school_id', $id)->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de materias por Escuela");
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
            'name'      => 'required|string|max:200',
            'code'      => 'required|string|max:200',
        ]);
        School::findOrFail($id);

        $newCourse = Course::create([
            'school_id'  => $id,
            'name'       => $fields['name'],
            'code'       => $fields['code'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de materias por escuela", "medium");
        return response([
            'course' => $newCourse,
        ], 201);
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
            'name'     => 'required|string|max:200',
            'code' => 'required|string|max:200',
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
