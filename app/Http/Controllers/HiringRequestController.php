<?php

namespace App\Http\Controllers;

use App\Models\HiringRequest;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use App\Http\Traits\WorklogTrait;
use Illuminate\Support\Facades\Auth;

class HiringRequestController extends Controller
{
    use WorklogTrait;

    public function store(StoreHiringRequestRequest $request)
    {   
        $request->request->add(['hiring_request_code'=>uniqid('HR_'),]); //Aqui va el generador de codigos de peñate
        $newHiringRequest = HiringRequest::create($request->all());
        $this->RegisterAction("El usuario ha registrado una nueva solicitud de contratación", "high");
        return response(['hiringRequest' => $newHiringRequest], 201);
    }

    
    public function show(HiringRequest $hiringRequest)
    {
        //
    }

    public function update(UpdateHiringRequestRequest $request, HiringRequest $hiringRequest)
    {
        //
    }

    public function destroy(HiringRequest $hiringRequest)
    {
        //
    }
}
