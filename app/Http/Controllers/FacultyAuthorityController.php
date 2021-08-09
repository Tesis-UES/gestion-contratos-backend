<?php

namespace App\Http\Controllers;

use App\Models\FacultyAuthority;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;

class FacultyAuthorityController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $facultyAuthorities = FacultyAuthority::with('faculty')->get();
        return response($facultyAuthorities ,200);
    }

    public function authoritiesByFaculty($id)
    {
        $facultyAuthoritie = FacultyAuthority::with('faculty')->where('faculty_id',$id)->get();
        return response($facultyAuthoritie,200);
    }

    public function show($id)
    {
        $facultyAuthoritie = FacultyAuthority::with('faculty')->findOrFail($id);
        return response($facultyAuthoritie, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              =>'required',
            'position'          =>'required',
            'faculty_id'        =>'required',
            'startPeriod'       => 'required',
            'endPeriod'         => 'required|after:startPeriod',
        ]);
        $newfacultyAuthoritie = FacultyAuthority::create($request->all());
        return response($newfacultyAuthoritie, 201);
    }

    
    

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              =>'required',
            'position'          =>'required',
            'faculty_id'        =>'required',
            'startPeriod'       => 'required',
            'endPeriod'         => 'required|after:startPeriod',
        ]);
        $facultyAuthority = FacultyAuthority::findOrFail($id);
        $facultyAuthority->update($request->all());
        return response($facultyAuthority, 200);
    }

   
    public function destroy($id)
    {
        $authority = FacultyAuthority::findOrFail($id);
        $authority->delete();
        return response(null, 204);
    }
}
