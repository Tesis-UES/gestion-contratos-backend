<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Semester;
use App\Imports\GroupCoursesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DB;

class GroupController extends Controller
{
  
    
    
    public function store(Request $request, $academicLoadId)
    {
        $fields = $request->validate([ 
            'number'                => 'required|integer',
            'group_type_id'         => 'required|integer',
            'course_id'             => 'required|integer',
            'people_id'             => 'nullable|integer',
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_hour'  => 'required|date_format:H:i',
            'details.*.finish_hour' => 'required|date_format:H:i|after:details.*.start_hour',
            'modality'              => ['required', Rule::in(['Presencial','En Linea'])],
        ]);
       
        $academicLoad = AcademicLoad::where('id', $academicLoadId)->firstOrFail();
        $result = Group::where(['academic_load_id'  => $academicLoadId,
                                'group_type_id'     =>$fields['group_type_id'],
                                'number'            =>$fields['number'],
                                'course_id'         =>$fields['course_id'],
                                'modality'          =>$fields['modality']])->get();
        if ($result->isEmpty()) {
            $Group = Group::create(array_merge($fields, ['academic_load_id' => $academicLoadId],['status' => (array_key_exists('people_id',$fields)) ? 'DA' :'SDA']));
            $Group->schedule()->createMany($fields['details']);
             $newGroup = [
                'number'            =>  $Group->number,          
                'type_group'        =>  $Group->grupo->name,  
                'curse_code'        =>  $Group->course->code,
                'course_name'       =>  $Group->course->name, 
                'modality'          =>  $Group->modality,                 
                'assigned_people'   =>  $retVal = ($Group->people_id == null) ? 'Sin Asignar Docente' :$Group->candidato->first_name." ".$Group->candidato->middle_name." ".$Group->candidato->last_name." ",
                'schedules'         =>  $Group->schedule()->get()
            ];
    
            return response($newGroup, 200); 
        } else {
            $message = "ya existe un grupo registrado con ese numero de grupo registrado en el sistema en la modalidad ".$fields['modality']."";
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
                'modality'          =>  $group->modality,
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
                'modality'          =>  $group->modality,
                'people_name'     =>" ".$group->candidato->first_name." ".$group->candidato->middle_name." ".$group->candidato->last_name." ",
                'schedules'         =>  $group->schedule()->get()
            ]; 
        }
        return response(['Group' =>  $Group], 200);
    }

    public function showByAcademicLoad($id){
        $academicLoad = AcademicLoad::where('id',$id)->select('id','semester_id')
        ->with(['semester'=> function($query){
            $query->select('id','status');
        }])
        ->with(['groups'=> function ($query) { 
                    $query->with(['grupo'=>function($query){
                        $query->select('id','name AS group_type_name');
                    }])
                    ->with(['course'=>function($query){
                        $query->select('id', 'name AS course_name', 'code AS curse_code');
                    }])
                    ->with(['candidato'=>function($query){
                        $query->select('id', DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) AS people_name"));
                    }])
                    ->with('schedule');
                }
        ])->firstOrFail();
         $academicLoad->groups->makeHidden(['created_at','updated_at','group_type_id','academic_load_id','course_id','people_id','deleted_at']);
         $academicLoad->groups->each(function($group){
             $group->schedule->makeHidden(['created_at','updated_at','group_id','deleted_at']);
            });
        return response([
            'academic_load' => $academicLoad
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
        $Group->update(array_merge($fields,['status' => 'DA']));
        
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
        $Group->status = 'DA';
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
            'modality'          =>  $Group->modality,
            'people_name'     =>" ".$Group->candidato->first_name." ".$Group->candidato->middle_name." ".$Group->candidato->last_name." ",
            'schedules'         =>  $Group->schedule()->get(),
        ];

        return response($updateGroup, 200); 
    }

    public function getAllGroupsWhitoutProfessors($modality){
        $semester = Semester::where('status',1)->firstOrFail();
        $school = $user = Auth::user()->school_id;
        $academicLoad = AcademicLoad::where([['semester_id',$semester->id],['school_id',$school]])->first();
        $groups = Group::with('course')->with('grupo')->with('schedule')->where([['academic_load_id',$academicLoad->id],['status','SDA'],['modality',$modality]])->get();
        return $groups;
    }

    public function getHiringGroups(Request $request){
        $groupsHiring = Group::with('course')->with('grupo')->with('schedule')->whereIn('id',$request->groups)->get();
        return $groupsHiring;
    }
}
