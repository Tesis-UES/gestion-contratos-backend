<?php

namespace App\Http\Controllers;

use App\Models\StudyPlan;
use App\Models\Course;
use App\Models\School;
use App\Http\Traits\WorklogTrait;
use Illuminate\Http\Request;

class StudyPlanController extends Controller
{
    public function all()
    {
        $studyplans = StudyPlan::all();
        return response($studyplans, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'school_id' => 'required|integer'
        ]);
        $newStudyPlan = StudyPlan::create($request->all());
        return response($newStudyPlan, 201);
    }

    public function show($id)
    {
        $studyplan = StudyPlan::findOrFail($id);
        return response($studyplan, 200);
    }

    public function showPlanSchool($id)
    {
        $studyplan = StudyPlan::where('school_id',$id)->get();
        return response($studyplan, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'school_id' => 'required'
        ]);
        $studyplan = StudyPlan::findOrFail($id);
        $studyplan->update($request->all());
        return response($studyplan, 200);
    }

    public function destroy($id)
    {
        $StudyPlan = StudyPlan::findOrFail($id);
        $Course = Course::where('study_plan_id',$id)->get();
        if ($Course->count() > 0) {
            return response(['message' => 'No se puede eliminar el plan de estudio porque tiene materias asociadas'], 422);
        } else {
        $StudyPlan->delete();
        return response(null, 204);
        }
    }
}
