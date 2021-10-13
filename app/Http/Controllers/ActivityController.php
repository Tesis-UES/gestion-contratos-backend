<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use WorklogTrait;

    public function all()
    {
        $activities = Activity::all();
        $this->RegisterAction('El usuario ha consultado el catalogo de actividades');
        return response($activities, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate(['name'=> 'required|string|max:100']);

        $existingActivity = Activity::where('name', 'ilike', $fields['name'])->first();
        if($existingActivity != null) {
            return response(['message' => 'Ya existe una actividad con ese nombre'], 400);
        }

        $newActivity = Activity::create($request->all());

        $this->RegisterAction('El usuario ha creado la actividad con ID: ' . $newActivity['id']);
        return response($newActivity, 201);
    }

    public function show($id)
    {
        $activty = Activity::findOrFail($id);
        $this->RegisterAction('El usuario ha consultado la actividad con ID: ' . $id);
        return response($activty, 200);
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        $this->RegisterAction('El usuario ha archivado la actividad con ID: ' . $id);
        return response(null, 204);
    }
}
