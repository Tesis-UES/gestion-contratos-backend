<?php

namespace App\Http\Controllers;

use App\Models\GroupType;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;


class GroupTypeController extends Controller
{
    
    use WorklogTrait;
    public function all()
    {
        $groupTypes = GroupType::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de tipos de Grupos de clase");
        return response($groupTypes, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string|unique:group_types,name|max:120', 
        ]);
        $newGroupTypes = GroupType::create([
            'name'     => $fields['name'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de tipos de grupo de clase");
        return response([
            'GroupTypes' => $newGroupTypes,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $groupTypes = GroupType::findorFail($id);
        return response(['groupTypes' => $groupTypes], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:120',
           
        ]);

        $GroupTypes = GroupType::findOrFail($id);
        $GroupTypes->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del tipo de gurpo  ".$request['name']." en el catalogo  tipo de grupos de clase");
        return response(['GroupTypes' => $GroupTypes], 200);
    }

    
    public function destroy($id)
    {
        $GroupTypes = GroupType::findOrFail($id);
        $GroupTypes->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del grupo de clase tipo  ".$GroupTypes->name." en el tipo de grupos de clase");
        return response(null, 204);
    }


}
