<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class FacultyController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $faculties = Faculty::select('faculties.id AS facultyId','faculties.name AS nameFaculty','faculty_authorities.name AS deanName')
        ->join('faculty_authorities','faculties.id','=','faculty_authorities.faculty_id','full outer')
        ->where('faculty_authorities.status','=',1)
        ->where('faculty_authorities.position','=','DECANO')
        ->orWhere('faculty_authorities.position','=',null)
        ->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de facultades");
        return response($faculties, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string|max:120',
        ]);

        $newFaculty = Faculty::create([
            'name'     => $fields['name'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de facultades", "medium");
        return response([
            'faculty' => $newFaculty,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = Faculty::findorFail($id);
        return response(['faculty' => $faculty], 200);
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
            'name'     => 'required|string|max:120',
        ]);

        $faculty = Faculty::findOrFail($id);
        $faculty->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro de la ".$request['name']." en el catalogo de facultades", "medium");
        return response(['faculty' => $faculty], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $school = School::where('faculty_id', $id)->get();
        if ($school->count() > 0) {
            return response(['message' => 'No se puede eliminar esta facultad porque tiene escuelas asociadas'], 422);
        } else {
            $faculty->delete();
            $this->RegisterAction("El usuario ha eliminado el registro de la ".$faculty->name." en el catalogo de facultades", "medium");
            return response(null, 204);
        }

       
    }
}
