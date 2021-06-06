<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function all($id)
    {
        $Schools = School::where('faculty_id',$id)->get();
        return response($Schools, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $fields = $request->validate([
            'name'     => 'required|string|max:200',
            'director' => 'required|string|max:200',
        ]);

        $newSchool = School::create([
            'faculty_id'     => $id,
            'name'     => $fields['name'],
            'director' => $fields['director'],
        ]);

        return response([
            'School' => $newSchool,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $School = School::findorFail($id);
        return response(['School' => $School], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:200',
            'director' => 'required|string|max:200',
        ]);

        $School = School::findOrFail($id);
        $School->update($request->all());

        return response(['School' => $School], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $School = School::findOrFail($id);
        $School->delete();
        return response(null, 204);
    }
}
