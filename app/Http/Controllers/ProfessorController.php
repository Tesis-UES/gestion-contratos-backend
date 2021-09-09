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
        
        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $professor = Professor::where('person_id', $person->id )->first();
        if($professor) { 
            return response(['message' => 'El usuario ya se registro como profesor'], 422);
        }

        $newProfessor = Professor::create([
            'person_id'     => $person->id,
            'escalafon_id'  => $fields['escalafon_id'],
        ]);

        $this->RegisterAction('El usuario se ha registrado como profesor', 'medium');
        return response($newProfessor, 201);
    }

    public function hasRegistered()
    {
        $professor = Auth::user()->person->professor;
        if($professor) {
            return response(['has_registered' => true], 200);
        }
        return response(['has_registered' => false], 200);
    }
}
