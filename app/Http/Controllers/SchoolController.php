<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function all($id)
    {
        Faculty::findOrFail($id);

        $schools = School::where('faculty_id', $id)->get();
        return response($schools, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $fields = $request->validate([
            'name'     => 'required|string|max:200',
            'director' => 'required|string|max:200',
        ]);
        Faculty::findOrFail($id);

        $newSchool = School::create([
            'faculty_id' => $id,
            'name'       => $fields['name'],
            'director'   => $fields['director'],
        ]);

        return response([
            'school' => $newSchool,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $school = School::findorFail($id);
        return response(['school' => $school], 200);
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

        $school = School::findOrFail($id);
        $school->update($request->all());

        return response(['School' => $school], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return response(null, 204);
    }
}
