<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
use Illuminate\Support\Facades\Auth;
class AcademicLoadController extends Controller
{
   
    use WorklogTrait;
    
    public function store(Request $request)
    {
        $fields = $request->validate([
            'school_id'     => 'required',
            'semester_id'   => 'required',
        ]);

        $result = AcademicLoad::where(['school_id'=>$request->school_id,'semester_id'=>$request->semester_id])->first();
        if ($result == null) {
            $newAcademicLoad = AcademicLoad::create($request->all());
            $this->RegisterAction("El usuario ha registrado una nueva carga academica para la escuela.");
             return response([
                'academicLoad' => $newAcademicLoad,
               ], 201);
        } else {
            return response([
                'message' => "No se puede Registrar carga academica de esta escuela ya que esta ya posee una creada.",
            ], 422);
        }
        
    }

    
    public function show($id)
    {
        $result = AcademicLoad::findOrFail($id);
        $academicLoad = [
            'id'            => $result->id,
            'semester_id'   => $result->semester_id,
            'school_id'     => $result->school_id,
            'semester'      => $result->semester->name,
            'school'        => $result->school->name,
            
        ];
        return response(['academicLoad' =>  $academicLoad,], 200);
    }

    
    
    public function academicLoadsSchool()
    {
        $user = Auth::user();
        $result = AcademicLoad::where(['school_id'=>$user->school_id])->with('semester')->get();
        return response(['academicLoad' =>  $result,], 200);
    }

    
}
