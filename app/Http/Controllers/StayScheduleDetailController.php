<?php

namespace App\Http\Controllers;

use App\Models\StaySchedule;
use App\Models\StayScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StayScheduleDetailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'stay_schedule_id'      => 'required|integer|gte:1',
            'details'               => 'required|array|min:1',
            'details.*.day'         => 'required|string',
            'details.*.start_time'  => 'required|date_format:H:i',
            'details.*.finish_time' => 'required|date_format:H:i|after:details.*.start_time',
        ]);

        $person = Auth::user()->person;
        if(!$person) {
            return response(['message' => 'Registre sus datos personales primero'], 400);
        }
        $professor = $person->professor;
        if(!$professor) {
            return response(['message' => 'Registrese como profesor primero', 400]);
        }

        $staySchedule = StaySchedule::where([
            'id'            => $fields['stay_schedule_id'], 
            'professor_id'  => $professor->id,
        ])->with('semester')->first();
        if(!$staySchedule->semester->status){
            return response(['message' => 'No se puede editar horario de permanencia de ciclos inactivos'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\stayScheduleDetail  $stayScheduleDetail
     * @return \Illuminate\Http\Response
     */
    public function show(stayScheduleDetail $stayScheduleDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\stayScheduleDetail  $stayScheduleDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(stayScheduleDetail $stayScheduleDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\stayScheduleDetail  $stayScheduleDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, stayScheduleDetail $stayScheduleDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\stayScheduleDetail  $stayScheduleDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(stayScheduleDetail $stayScheduleDetail)
    {
        //
    }
}
