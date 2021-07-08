<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class FacultyController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $faculties = Faculty::all();
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
            'dean'     => 'required|string|max:200',
            'viceDean' => 'required|string|max:200',
        ]);

        $newFaculty = Faculty::create([
            'name'     => $fields['name'],
            'dean'     => $fields['dean'],
            'viceDean' => $fields['viceDean'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de facultades");
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
            'dean'     => 'required|string|max:200',
            'viceDean' => 'required|string|max:200',
        ]);

        $faculty = Faculty::findOrFail($id);
        $faculty->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro de la ".$request['name']." en el catalogo de facultades");
        return response(['faculty' => $faculty], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();
        $this->RegisterAction("El usuario ha eliminado el registro de la ".$faculty->name." en el catalogo de facultades");
        return response(null, 204);
    }
}
