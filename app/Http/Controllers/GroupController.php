<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Schedule;
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
            'days'              =>'required|array|min:1',
            'days.*'            =>'required|string',
            'start_hours'       =>'required|array|min:1',
            'start_hours.*'     =>'required|string',
            'finish_hours'      =>'required|array|min:1',
            'finish_hours.*'    =>'required|string',
        ]);
       
        $days = $request->days;
        $startHours = $request->start_hours;
        $endHours = $request->finish_hours;

        foreach ($days as $key => $day) {
            $newSchedule = new Schedule([
                'day'               =>$day,
                'start_hour'        =>$startHours[$key],
                'finish_hour'       =>$endHours[$key]
            ]);
            $nuevos[] =  $newSchedule;
        }
        $Group = Group::create($fields);
        $Group->schedule()->saveMany($nuevos);
        
         $newGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'type_group'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $Group->academic_load_id, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'professor_id'      =>  $Group->professor_id, 
            'schedules'         =>  $Group->schedule()->get()
        ];

        return response(['newGroup' =>  $newGroup], 200); 
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
            'number'            =>'required|integer',
            'group_type_id'     =>'required|integer',
            'academic_load_id'  =>'required|integer',
            'course_id'         =>'required|integer',
            'professor_id'      =>'required|integer',
        ]);
       
        $days = $request->days;
        $startHours = $request->start_hours;
        $endHours = $request->finish_hours;
        $Group = Group::findorFail($id);
        $Group->update($fields);
        Schedule::where('group_id','=',$id)->delete();
        foreach ($days as $key => $day) {
            $newSchedule = new Schedule([
                'day'               =>$day,
                'start_hour'        =>$startHours[$key],
                'finish_hour'       =>$endHours[$key]
            ]);
            $nuevos[] =  $newSchedule;
        }
        $Group->schedule()->saveMany($nuevos);
        $updateGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'type_group'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $Group->academic_load_id, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'professor_id'      =>  $Group->professor_id, 
            'schedules'         =>  $Group->schedule()->get()
        ];

        return response(['updateGroup' => $updateGroup], 200); 
    }

  
    public function destroy(Group $group)
    {
        //
    }
}
