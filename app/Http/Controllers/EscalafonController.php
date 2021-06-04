<?php

namespace App\Http\Controllers;

use App\Models\Escalafon;
use Illuminate\Http\Request;

class EscalafonController extends Controller
{

    public function all()
    {
        $Escalafons = Escalafon::all();
        $response = [
            'escalafon' => $Escalafons,
        ];
        return response($response, 200);
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
            'code'            => 'required|string',
            'name'   => 'required|string|unique:escalafons,name',
            'salary'  => 'required|integer',
        ]);

        $newEscalafon = Escalafon::create([
            'code'            => $fields['code'],
            'name'   => $fields['name'],
            'salary'  => $fields['salary'],
        ]);

        $response = [
            'escalafon' => $newEscalafon,
        ];
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $response = [
                'escalafon' => Escalafon::find($id)->get(),
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            return response(['mensaje' => "Escalafon no encontrado"], 404);
        }
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

        $fields = $request->validate([
            'code'      => 'required|string',
            'name'      => 'required|string',
            'salary'    => 'required|integer',
        ]);

        try {
            $escalafon = Escalafon::find($id);
            $escalafon->update($request->all());
            $response = ['escalafon' => $escalafon];
            return response($response, 200);
        } catch (\Throwable $th) {
            return response(['mensaje' => "Escalafon no encontrado"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $escalafon = Escalafon::find($id);
            $escalafon->delete();
            return response(null, 200);
        } catch (\Throwable $th) {
            return response(['mensaje' => "Escalafon no encontrado"], 404);
        }
    }
}
