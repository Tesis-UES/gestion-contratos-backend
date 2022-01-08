<?php

namespace App\Http\Controllers;

use App\Models\HiringRequestDetail;
use Illuminate\Http\Request;

class HiringRequestDetailController extends Controller
{
    public function addSPNPRequestDetails($id, Request $request) 
    {
        // request validate

        $hiringRequest = HiringRequest::findOrFail($id);
        if($hiringRequest->contract_type->name != 'Contrato por Servicios Profesionales') 
        {
            return response(['message' => 'Debe seleccionar un contrato del tipo "Contrato por Servicios Profesionales"', 400]);
        }

        // Guardar todo 
    }
}
