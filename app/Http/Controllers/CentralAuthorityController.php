<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\CentralAuthority;
use Illuminate\Http\Request;

class CentralAuthorityController extends Controller
{
    use WorklogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $authorities = CentralAuthority::all();
        $this->RegisterAction('El usuario ha consultado el catalogo de autoridades centrales');
        return response($authorities, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'position'      => 'required|string|max:120',
            'firstName'     => 'required|string|max:60',
            'middleName'    => 'string|max:60',
            'lastName'      => 'required|string|max:120',
            'dui'           => 'required|string|max:20',
            'nit'           => 'required|string|max:20',
            'startPeriod'   => 'required',
            'endPeriod'     => 'required|after:startPeriod',
        ]);
        $newAuthority = CentralAuthority::create($request->all());

        $this->RegisterAction('El usuario ha creado la autoridad central con ID: ' . $newAuthority['id']);
        return response($newAuthority, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authority = CentralAuthority::findOrFail($id);
        $this->RegisterAction('El usuario ha visto la autoridad central con ID: ' . $id);
        return response($authority, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'position'    => 'required|string|max:120',
            'firstName'   => 'required|string|max:60',
            'middleName'  => 'string|max:60',
            'lastName'    => 'required|string|max:120',
            'dui'         => 'required|string|max:20',
            'nit'         => 'required|string|max:20',
            'startPeriod'   => 'required',
            'endPeriod'     => 'required|after:startPeriod',
        ]);

        $authority = CentralAuthority::findOrFail($id);
        $authority->update($request->all());
        $this->RegisterAction('El usuario ha actualizado la autoridad central con ID : ' . $id);
        return response($authority, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authority = CentralAuthority::findOrFail($id);
        $authority->delete();

        $this->RegisterAction('El usuario ha borrado la autoridad central con ID: ' . $id);
        return response(null, 204);
    }
}
