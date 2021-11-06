<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use App\Models\Group;
use App\Models\Schedule;
use App\Imports\GroupCoursesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GroupController extends Controller
{
  
    
    
    public function store(Request $request, $academicLoadId)
    {
        $fields = $request->validate([ 
            'number'                => 'required|integer',
            'group_type_id'         => 'required|integer',
            'course_id'             => 'required|integer',
            'people_id'             => 'integer',
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_hour'  => 'required|date_format:H:i',
            'details.*.finish_hour' => 'required|date_format:H:i|after:details.*.start_hour',
        ]);
       
        $academicLoad = AcademicLoad::where('id', $academicLoadId)->firstOrFail();
        $result = Group::where(['academic_load_id'  => $academicLoadId,
                                'group_type_id'     =>$fields['group_type_id'],
                                'number'            =>$fields['number'],
                                'course_id'         =>$fields['course_id']])->get();
        if ($result->isEmpty()) {
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
                'people_id'         =>  $Group->people_id, 
                'schedules'         =>  $Group->schedule()->get()
            ];
    
            return response($newGroup, 200); 
        } else {
            $message = "ya existe un grupo registrado con ese numero de grupo registrado en el sistema";
            return response($message, 400);
        }
        
        
    }

   
    public function show($id)
    {
        $group = Group::findorFail($id);
        if ($group->candidato == null) {
            $Group = [
                'id'                =>  $group->id,
                'number'            =>  $group->number,          
                'group_type_id'     =>  $group->group_type_id,
                'type_group'        =>  $group->grupo->name,  
                'academic_load_id'  =>  $group->academic_load_id, 
                'course_id'         =>  $group->course_id,
                'nombre_curso'      =>  $group->course->name,                   
                'people_id'         =>  $group->people_id, 
                'people_name'       =>  "Sin Asignar",
                'schedules'         =>  $group->schedule()->get()
            ]; 
        }else{
            $Group = [
                'id'                =>  $group->id,
                'number'            =>  $group->number,          
                'group_type_id'     =>  $group->group_type_id,
                'type_group'        =>  $group->grupo->name,  
                'academic_load_id'  =>  $group->academic_load_id, 
                'course_id'         =>  $group->course_id,
                'nombre_curso'      =>  $group->course->name,                   
                'people_id'         =>  $group->people_id, 
                'people_name'     =>" ".$group->candidato->first_name." ".$group->candidato->middle_name." ".$group->candidato->last_name." ",
                'schedules'         =>  $group->schedule()->get()
            ]; 
        }
        return response(['Group' =>  $Group], 200);
    }

    public function showByAcademicLoad($id){
        $academicLoad = AcademicLoad::where('id',$id)->with('semester')->firstOrFail();
        $group = Group::with('course')->with('grupo')->with('schedule')->where('academic_load_id','=',$id)->get();
        $groups = [];
        foreach ($group as $gp) {
           
            $horario =[];
            foreach ($gp->schedule as $sch) {
                $hr =[
                    'day'           =>$sch->day,
                    'start_hour'    =>$sch->start_hour,
                    'finish_hour'   =>$sch->finish_hour,
                ];
                array_push($horario,$hr);
                
            }
            if ($gp->candidato == null) {
                $grp =[
                    'id'    => $gp->id,
                    'number'=> $gp->number,
                    'group_type_name' =>$gp->grupo->name,
                    'course_code'     =>$gp->course->code,
                    'course_name'     =>$gp->course->name,
                    'people_name'     =>"Sin Docente Asignado",
                    'schedule'        =>$horario
                ];
            }else{
                $grp =[
                    'id'    => $gp->id,
                    'number'=> $gp->number,
                    'group_type_name' =>$gp->grupo->name,
                    'course_code'     =>$gp->course->code,
                    'course_name'     =>$gp->course->name,
                    'people_name'     =>" ".$gp->candidato->first_name." ".$gp->candidato->middle_name." ".$gp->candidato->last_name." ",
                    'schedule'        =>$horario
                ];
            }
            
           array_push($groups,$grp);
        }
       
        return response([
            'groups' =>  $groups, 
            'semesterActive'=> $academicLoad->semester->status
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([ 
            'number'                => 'required|integer',
            'group_type_id'         => 'required|integer',
            'course_id'             => 'required|integer',
            'people_id'             => 'integer',
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
            'people_id'         =>  $Group->people_id, 
            'people_name'     =>" ".$Group->candidato->first_name." ".$Group->candidato->middle_name." ".$Group->candidato->last_name." ",
            'schedules'         =>  $Group->schedule()->get(),
        ];

        return response($updateGroup, 200); 
    }

    public function importGroups(Request $request, $academicLoadId){

        $import =  new GroupCoursesImport($academicLoadId);
        Excel::import($import, $request->file('excel'));
        return response(['DatosMalos'=>$import->resultM, 'DatosBuenos'=>$import->resultB], 200);        
    }

    public function setProfessor(Request $request, $id){
        $fields = $request->validate([ 
            'people_id'             => 'required|integer',
           
        ]);

        $Group = Group::findorFail($id);
        $Group->people_id = $fields['people_id'];
        $Group->save();
    

        $updateGroup = [
            'id'                =>  $Group->id,
            'number'            =>  $Group->number,          
            'group_type_id'     =>  $Group->group_type_id,
            'group_type'        =>  $Group->grupo->name,  
            'academic_load_id'  =>  $Group->academic_load_id, 
            'course_id'         =>  $Group->course_id,
            'nombre_curso'      =>  $Group->course->name,                   
            'people_id'         =>  $Group->people_id, 
            'people_name'     =>" ".$Group->candidato->first_name." ".$Group->candidato->middle_name." ".$Group->candidato->last_name." ",
            'schedules'         =>  $Group->schedule()->get(),
        ];

        return response($updateGroup, 200); 
    }
}
