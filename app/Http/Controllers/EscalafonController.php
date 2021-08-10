<?php

namespace App\Http\Controllers;

use App\Models\Escalafon;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class EscalafonController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $escalafons = Escalafon::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de Escalafones");
        return response($escalafons, 200);
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
            'code'   => 'required|string|max:10',
            'name'   => 'required|string|max:120',
            'salary' => 'required|integer',
        ]);

        $newEscalafon = Escalafon::create([
            'code'   => $fields['code'],
            'name'   => $fields['name'],
            'salary' => $fields['salary'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de Escalafones", "medium");
        return response([
            'escalafon' => $newEscalafon,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $escalafon = Escalafon::findOrFail($id);
        return response(['escalafon' => $escalafon,], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code'   => 'required|string|max:10',
            'name'   => 'required|string|max:120',
            'salary' => 'required|integer',
        ]);

        $escalafon = Escalafon::findOrFail($id);
        $escalafon->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del escalafon ".$request['name']." en el catalogo de escalafones", "medium");

        return response(['escalafon' => $escalafon], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $escalafon = Escalafon::findOrFail($id);
        $escalafon->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del escalafon ".$escalafon->name." en el catalogo de Escalafones" , "medium");
        return response(null, 204);
    }
}
