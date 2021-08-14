<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  
    
    
    public function store(Request $request)
    {
        $fields = $request->validate([ 
            'number'            =>'required|integer',
            'group_type_id'     =>'required|integer',
            'academic_load_id'  =>'required|integer',
            'course_id'         =>'required|integer',
            'professor_id'      =>'required|integer',
        ]);
        $Group = Group::create($fields);
        $newGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'type_group'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $Group->academic_load_id, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'professor_id'      =>  $Group->professor_id,  
        ];
        return response(['newGroup' =>  $newGroup], 200);
        
    }

   
    public function show(Group $group)
    {
        //
    }

    
    public function edit(Group $group)
    {
        //
    }

    
    public function update(Request $request, Group $group)
    {
        //
    }

  
    public function destroy(Group $group)
    {
        //
    }
}
