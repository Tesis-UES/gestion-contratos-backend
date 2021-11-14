<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    use WorklogTrait;

    public function all()
    {
        $positions = Position::orderBy('name', 'asc')->get();
        $this->RegisterAction('El usuario ha consultado el catalogo de cargos');
        return response($positions, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|string|max:100',
            'activities'    => 'required|array|min:2',
            'activities.*'  => 'required|string|distinct',
        ]);

        $existingPosition = Position::where('name', 'ilike', $fields['name'])->first();
        if($existingPosition != null) {
            return response(['message' => 'Ya existe un cargo con ese nombre'], 400);
        }

        foreach($fields['activities'] as $activityName) {
            $activity = Activity::where('name', 'ilike', $activityName)->first();
            if(!$activity){
                $activity = Activity::create(['name' => $activityName]);
            }
            $activities[] = $activity;
        }

        $newPosition = Position::create([
            'name' => $fields['name'],
        ]);
        $newPosition->activities()->saveMany($activities);
        $newPosition->activities = $activities;
        
        $this->RegisterAction('El usuario ha creado el cargo con ID: ' . $newPosition->id);
        return response($newPosition, 200);
    }

    public function update($id, Request $request) {
        $fields = $request->validate([
            'name'          => 'required|string|max:100',
            'activities'    => 'required|array|min:2',
            'activities.*'  => 'required|string|distinct',
        ]);

        $position = Position::findOrFail($id);

        $otherPosition = Position::where('name', 'ilike', $fields['name'])->whereNull('deleted_at')->first();
        if ($otherPosition != null && $otherPosition->id != $id) {
            return response(['message' => 'Ya existe un cargo con ese nombre'], 400);
        }

        foreach($fields['activities'] as $activityName) {
            $activity = Activity::where('name', 'ilike', $activityName)->first();
            if(!$activity){
                $activity = Activity::create(['name' => $activityName]);
            }
            $activityIds[] = $activity->id;
        }
        
        $position->name = $fields['name'];
        $position->activities()->sync($activityIds);
        
        $position->save();

        $this->RegisterAction('El usuario ha actualizado el cargo con ID: ' . $id);
        return response($position, 200);
    }

    public function show($id)
    {
        $position = Position::where('id', $id)->with('activities')->firstOrFail();
        $this->RegisterAction('El usuario ha consultado el cargo con ID: ' . $id);
        return response($position, 200);
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        $this->RegisterAction('El usuario ha archivado el cargo con ID: ' . $id);
        return response(null, 204);
    }
}
