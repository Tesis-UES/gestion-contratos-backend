<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class EmployeeTypeController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $EmployeeTypes = EmployeeType::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de tipos de empleados");
        return response($EmployeeTypes, 200);
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
            'name'              => 'required|string|max:120',    
        ]);

        $newEmployeeType = EmployeeType::create([
            'name'          => $fields['name'],    
        ]);
        $this->RegisterAction("El usuario ha Ingresado un tipo de empleado", "medium");
        return response([
            'EmployeeType' => $newEmployeeType,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeType  $EmployeeType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $EmployeeType = EmployeeType::findOrFail($id);
        return response(['EmployeeType' => $EmployeeType,], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeType  $EmployeeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:120',
        ]);

        $EmployeeType = EmployeeType::findOrFail($id);
        $EmployeeType->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del tipo de empleado  " . $request['name'] . " en el catalogo de tipos de empleados", "medium");

        return response(['EmployeeType' => $EmployeeType], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeType  $EmployeeType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $EmployeeType = EmployeeType::findOrFail($id);
        $EmployeeType->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del tipo de empleado " . $EmployeeType->name . " en el catalogo de EmployeeTypees", "medium");
        return response(null, 204);
    }
}
