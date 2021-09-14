<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use App\Models\Group;
use App\Models\Schedule;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  
    
    
    public function store(Request $request, $academicLoadId)
    {
        $fields = $request->validate([ 
            'number'                => 'required|integer',
            'group_type_id'         => 'required|integer',
            'course_id'             => 'required|integer',
            'professor_id'          => 'required|integer',
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_hour'  => 'required|date_format:H:i',
            'details.*.finish_hour' => 'required|date_format:H:i|after:details.*.start_hour',
        ]);
       
        $academicLoad = AcademicLoad::where('id', $academicLoadId)->firstOrFail();
        $Group = Group::create(array_merge($fields, ['academic_load_id' => $academicLoadId]));
        $Group->schedule()->createMany($fields['details']);
        
         $newGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'type_group'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $academicLoadId, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'professor_id'      =>  $Group->professor_id, 
            'schedules'         =>  $Group->schedule()->get()
        ];

        return response($newGroup, 200); 
    }

   
    public function show($id)
    {
        $group = Group::findorFail($id);
        $Group = [
            'id'                =>  $group->id,
            'number'            =>  $group->number,          
            'group_type_id'     =>  $group->group_type_id,
            'type_group'        =>  $group->grupo->name,  
            'academic_load_id'  =>  $group->academic_load_id, 
            'course_id'         =>  $group->course_id,
            'nombre_curso'      =>  $group->course->name,                   
            'professor_id'      =>  $group->professor_id, 
            'schedules'         =>  $group->schedule()->get()
        ];
        return response(['Group' =>  $Group], 200);
    }

    public function showByAcademicLoad($id){
        $group = Group::with('course')->with('grupo')->with('schedule')->where('academic_load_id','=',$id)->get();
        return response(['groups' =>  $group], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([ 
            'number'                => 'required|integer',
            'group_type_id'         => 'required|integer',
            'academic_load_id'      => 'required|integer',
            'course_id'             => 'required|integer',
            'professor_id'          => 'required|integer',
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_hour'  => 'required|date_format:H:i',
            'details.*.finish_hour' => 'required|date_format:H:i|after:details.*.start_hour',
        ]);

        $Group = Group::findorFail($id);
        $Group->update($fields);
        
        Schedule::where('group_id',$id)->delete();
        $Group->schedule()->createMany($fields['details']);

        $updateGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'group_type'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $Group->academic_load_id, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'professor_id'      =>  $Group->professor_id, 
            'schedules'         =>  $Group->schedule()->get(),
        ];

        return response($updateGroup, 200); 
    }
}
