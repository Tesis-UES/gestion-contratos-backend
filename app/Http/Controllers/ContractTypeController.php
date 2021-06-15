<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class ContractTypeController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $contractTypes = ContractType::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de Contratos");
        return response($contractTypes, 200);
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
            'description'       => 'required|string|max:120',
        ]);

        $newContractType = ContractType::create([
            'name'          => $fields['name'],
            'description'   => $fields['description'],
            
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de Contratos");
        return response([
            'contractType' => $newContractType,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContractType  $ContractType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contractType = ContractType::findOrFail($id);
        return response(['contractType' => $contractType,], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractType  $ContractType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:120',
            'description'       => 'required|string|max:120',

        ]);

        $contractType = ContractType::findOrFail($id);
        $contractType->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del Contrato " . $request['name'] . " en el catalogo de Contratos");

        return response(['contractType' => $contractType], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContractType  $ContractType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contractType = ContractType::findOrFail($id);
        $contractType->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del ContractType " . $contractType->name . " en el catalogo de ContractTypees");
        return response(null, 204);
    }
}
