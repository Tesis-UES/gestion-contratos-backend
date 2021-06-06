<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
   
    public function all()
    {
        $Faculties = Faculty::all();
        $response = [
            'faculty' => $Faculties,
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
            'name'      => 'required|string',
            'dean'      => 'required|string',
            'viceDean'  => 'required|string',
        ]);

        $newFaculty = Faculty::create([
            'name'       => $fields['name'],
            'dean'       => $fields['dean'],
            'viceDean'   => $fields['viceDean'],
        ]);

        $response = [
            'faculty' => $newFaculty,
        ];
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = Faculty::find($id);
        if($faculty == null){
            return response(['mensaje' => "Facultad no encontrada"], 404);
        }else{
            
            $response = ['faculty' => $faculty];
            return response($response, 200);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $fields = $request->validate([
            'name'      => 'required|string',
            'dean'      => 'required|string',
            'viceDean'  => 'required|string',
        ]);
            $faculty = Faculty::find($id);
            if ($faculty == null) {
                return response(['mensaje' => "Facultad no encontrada"], 404);
            }else{
                $faculty->update($request->all());
                $response = ['faculty' => $faculty];
                return response($response, 200);
            }
            

       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

            $faculty = Faculty::find($id);
            if ($faculty == null) {
                return response(['mensaje' => "Facultad no encontrada"], 404);
            }else{
                $faculty->delete();
                return response(null, 200);
            }
    }




}
