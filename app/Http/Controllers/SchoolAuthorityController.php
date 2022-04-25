<?php

namespace App\Http\Controllers;

use App\Models\SchoolAuthority;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
class SchoolAuthorityController extends Controller
{
    
    use WorklogTrait;
    public function all()
    {
        $schoolAuthorities = SchoolAuthority::with('school')->get();
        return response($schoolAuthorities ,200);
    }

    public function authoritiesBySchool($id)
    {
        $schoolAuthoritie = SchoolAuthority::with('school')->where('school_id',$id)->get();
        return response($schoolAuthoritie,200);
    }

    public function show($id)
    {
        $schoolAuthoritie = SchoolAuthority::with('school')->findOrFail($id);
        return response($schoolAuthoritie, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              =>'required',
            'position'          =>'required',
            'school_id'        =>'required',
            'startPeriod'       => 'required',
            'endPeriod'         => 'required|after:startPeriod',
        ]);
        $newSchoolAuthoritie = SchoolAuthority::create($request->all());
        return response($newSchoolAuthoritie, 201);
    }

    public function changeStatus($id)
    {
        $schoolAuthority = SchoolAuthority::findOrFail($id);
        $schoolAuthority->status = !$schoolAuthority->status;
        $schoolAuthority->save();
        return response($schoolAuthority, 200);
    }

    
    

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              =>'required',
            'position'          =>'required',
            'school_id'        =>'required',
            'startPeriod'       => 'required',
            'endPeriod'         => 'required|after:startPeriod',
        ]);
        $schoolAuthority = SchoolAuthority::findOrFail($id);
        $schoolAuthority->update($request->all());
        return response($schoolAuthority, 200);
    }

   
    public function destroy($id)
    {
        $authority = SchoolAuthority::findOrFail($id);
        $authority->delete();
        return response(null, 204);
    }
}
