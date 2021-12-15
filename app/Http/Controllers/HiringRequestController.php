<?php

namespace App\Http\Controllers;

use App\Models\HiringRequest;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use App\Http\Traits\WorklogTrait;
use App\Http\Traits\GeneratorTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HiringRequestController extends Controller
{
    use WorklogTrait, GeneratorTrait;

    public function store(StoreHiringRequestRequest $request)
    {  
        $newHiringRequest = HiringRequest::create(array_merge($request->all(),[
            'hiring_request_code'   =>$this->generateRequestCode($request->school_id),
            'status'                =>'En creacion',
            'request_create'        => Carbon::now()
        ]));
        $this->RegisterAction("El usuario ha registrado una nueva solicitud de contratación", "high");
        return response(['hiringRequest' => $newHiringRequest], 201);
    }

    
    public function show($id)
    {
        $hiringRequest = HiringRequest::with('school')->with('contractType')->findOrFail($id);
        return response(['hiringRequest' => $hiringRequest], 200);
    }
   

    public function update(UpdateHiringRequestRequest $request, $id)
    {
        $hiringRequest = HiringRequest::findOrFail($id);
        $hiringRequest->update($request->all());
        $this->RegisterAction("El usuario ha actualizado la solicitud de contratación", "high");
        return response(['hiringRequest' => $hiringRequest], 200);
    }

    public function destroy($id)
    {
        $hiringRequest = HiringRequest::findOrFail($id);
        $hiringRequest->delete();
        $this->RegisterAction("El usuario ha eliminado la solicitud de contratación", "high");
        return response(null,204);
    }

    public function getAllHiringRequests(Request $request)
    {
        $hiringRequests = HiringRequest::where('status', '=', $request->query('status'))->with('school')->with('contractType')->paginate($request->query('paginate'));
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación", "medium");
        return response($hiringRequests, 200);
    }

    public function getAllHiringRequestBySchool($id,Request $request)
    {
        $hiringRequests = HiringRequest::where('school_id', '=', $id)->where('status', '=', $request->query('status'))->with('school')->with('contractType')->paginate($request->query('paginate'));
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación", "medium");
        return response($hiringRequests, 200);
    }
}
