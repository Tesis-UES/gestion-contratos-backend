<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Http\Traits\WorklogTrait;

class SemesterController extends Controller
{

    use WorklogTrait;
    public function all()
    {
        $semesters = Semester::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de historial de ciclo registrados en el sistema.");
        return response($semesters, 200);
    }

    public function allActives()
    {
        $semesters = Semester::where('status','=',1)->get();
        $this->RegisterAction("El usuario ha consultado el catalogo de historial de ciclo registrados en el sistema.");
        return response($semesters, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|string|max:120',
            'start_date'    => 'required|unique:semesters',
            'end_date'      => 'required|unique:semesters|after:start_date',     
        ]);

        $lastSemester = Semester::latest()->first();

        if ($lastSemester == null ) {
            $newSemester = Semester::create($request->all());
            $this->RegisterAction("El usuario ha Ingresado un nuevo registro de ciclo academico");
            return response([
            'Semester' => $newSemester,
            ], 201);
        
        } else {
            $baseDate   = new \DateTime($lastSemester->end_date); 
            $startDate  = new \DateTime($request->start_date);
        
            if ($baseDate < $startDate) {
            
                $newSemester = Semester::create($request->all());
                $this->RegisterAction("El usuario ha Ingresado un nuevo registro de ciclo academico");
                return response([
                    'Semester' => $newSemester,
                    ], 201);

            } else {
                return response([
                    'message' => "No se puede Crear el ciclo academico con fechas que traslapen un ciclo anterior",
                ], 422);
            }
        }
        
        
        
   
    }

    public function show($id)
    {
        $semester = Semester::findOrFail($id);
        return response(['semester' => $semester,], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:120',
            'start_date'    => 'required',
            'end_date'      => 'required',
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del ciclo  ".$request['name']." en el catalogo de ciclos activos");
        return response(['semester' => $semester], 200);
        
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del ciclo ".$semester->name." en el catalogo de ciclos");
        return response(null, 204);
    }


}
