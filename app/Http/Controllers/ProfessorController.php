<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Escalafon;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfessorController extends Controller
{
    use WorklogTrait;

    public function store(Request $request)
    {   
        $fields = $request->validate([
            'escalafon_id'     => 'required|integer|gte:1',
        ]);
        Escalafon::where('id', $fields['escalafon_id'])->firstOrFail();
        
        $personId = Auth::user()->person->id;
        $professor = Professor::where('person_id', $personId )->first();
        if($professor) { 
            return response(['message' => 'El usuario ya se registro como profesor'], 422);
        }

        $newProfessor = Professor::create([
            'person_id'     => $personId,
            'escalafon_id'  => $fields['escalafon_id'],
        ]);

        $this->RegisterAction("El usuario se ha registrado como profesor");
        return response([$newProfessor], 201);
    }
}
