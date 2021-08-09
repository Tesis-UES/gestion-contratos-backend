<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class SchoolController extends Controller
{
    use WorklogTrait;
    public function all($id)
    {
        Faculty::findOrFail($id);
        $schools = School::select('schools.id AS schoolId','schools.name AS nameSchool','school_authorities.name AS directorName')
        ->leftjoin('school_authorities','schools.id','=','school_authorities.school_id')
        ->where('school_authorities.status','=',1)
        ->where('school_authorities.position','=','DIRECTOR')
        ->where('schools.faculty_id', $id)->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de Escuelas");
        return response($schools, 200);
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
            'name'     => 'required|string|max:200',
        ]);
        Faculty::findOrFail($id);

        $newSchool = School::create([
            'faculty_id' => $id,
            'name'       => $fields['name'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de escuelas", "medium");
        return response([
            'school' => $newSchool,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $school = School::findorFail($id);
        return response(['school' => $school], 200);
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
        ]);

        $school = School::findOrFail($id);
        $school->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro de ".$request['name']." en el catalogo de escuelas por facultad", "medium");
        return response(['School' => $school], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        $this->RegisterAction("El usuario ha eliminado el registro de la escuela ".$school->name." en el catalogo de Escuelas", "medium");
        return response(null, 204);
    }
}
