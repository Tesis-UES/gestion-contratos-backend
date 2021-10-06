<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use WorklogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $activities = Activity::all();
        $this->RegisterAction('El usuario ha consultado el catalogo de actividades');
        return response($activities, 200);
    }

    /**
     * Display a listing of the recommended resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recommended(Request $request)
    {
        $type = $request->query('type');
        if(!($type == 'profesor' || $type == 'administrativo' || $type == 'jefe')) {
            return response(['message' => "type debe ser 'profesor' || 'administrativo' || 'jefe'"],400);
        }
        $activities = Activity::where('recommended', $type)->get();
        $this->RegisterAction('El usuario ha consultado el catalogo de actividades recomendadas del tipo ', $type);
        return response($activities, 200);
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
            'name'         => 'required|string|max:100',
            'recommended'  => 'boolean'
        ]);
        $newActivity = Activity::create($request->all());

        $this->RegisterAction('El usuario ha creado la actividad con ID: ' . $newActivity['id']);
        return response($newActivity, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activty  $activty
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activty = Activity::findOrFail($id);
        $this->RegisterAction('El usuario ha visto la actividad con ID: ' . $id);
        return response($activty, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activty  $activty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'recommended'  => 'boolean'
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update($request->all());
        $this->RegisterAction('El usuario ha actualizado la actividad con ID: ' . $id);

        return response($activity, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activty  $activty
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        $this->RegisterAction('El usuario ha borrado la actividad con ID: ' . $id);
        return response(null, 204);
    }
}
