<?php

namespace App\Http\Controllers;

use App\Models\HiringRequest;
use App\Models\Status;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use App\Http\Traits\WorklogTrait;
use App\Http\Traits\GeneratorTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants\HiringRequestStatusCode;
use App\Models\HiringRequestDetail;
use Illuminate\Support\Facades\DB;
use PDF;

class HiringRequestController extends Controller
{
    use WorklogTrait, GeneratorTrait;

    public function store(StoreHiringRequestRequest $request)
    {
        $newHiringRequest = HiringRequest::create(array_merge($request->all(), [
            'code'   => $this->generateRequestCode($request->school_id),
            'request_status' => HiringRequestStatusCode::CSC,
        ]));
        $status = Status::whereIn('code', ['CSC', 'RDC'])->orderBy('order')->get();
        $newHiringRequest->status()->attach(['status_id' => $status[0]->id]);
        $newHiringRequest->status()->attach(['status_id' => $status[1]->id]);
        $this->RegisterAction("El usuario ha registrado una nueva solicitud de contratación", "high");
        return response(['hiringRequest' => $newHiringRequest], 201);
    }


    public function show($id)
    {
        $hiringRequest = HiringRequest::with('school')->with('contractType')->with('status')->findOrFail($id);
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
        return response(null, 204);
    }

    public function getAllHiringRequests(Request $request)
    {
        $hiringRequests = HiringRequest::with('school')->with('contractType')->orderBy('created_at', 'DESC')->paginate($request->query('paginate'));
        $hiringRequests->makeHidden('status');
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación", "medium");
        return response($hiringRequests, 200);
    }

    public function getAllHiringRequestBySchool($id, Request $request)
    {
        $hiringRequests = HiringRequest::where('school_id', '=', $id)->with('school')->with('contractType')->orderBy('created_at', 'DESC')->paginate($request->query('paginate'));
        $hiringRequests->makeHidden('status');
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación", "medium");
        return response($hiringRequests, 200);
    }

    public function getAllHiringRequestsSecretary()
    {
        $hiringRequests = HiringRequest::where('request_status', HiringRequestStatusCode::EDS)->with('school')->with('contractType')->orderBy('created_at', 'DESC')->paginate(10);
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratacion enviadas a secretaria", "medium");
        return response($hiringRequests, 200);
    }
    public function secretaryReceptionHiringRequest(HiringRequest $hiringRequest, Request $request)
    {
        $request->validate([
            'approved'          => 'required|boolean',
            'observations'       => 'string|nullable',
        ]);

        if ($hiringRequest->request_status != HiringRequestStatusCode::EDS) {
            return response(['message' => 'Solo las solicitudes que tengan el estado de enviadas pueden ser dadas por recibidas por secretaria'], 400);
        }
        if ($request->approved) {
            $status = Status::where('code', HiringRequestStatusCode::RDS)->first();
            $comment = 'La solicitud fue aceptada por secretaria y pasara a ser agendada para ser vista en junta directiva';
            $hiringRequest->request_status = HiringRequestStatusCode::RDS;
        } else {
            $status = Status::where('code', HiringRequestStatusCode::ODS)->first();
            $comment = $request->observations;
            $hiringRequest->request_status = HiringRequestStatusCode::ODS;
        }
        $hiringRequest->status()->attach(['status_id' => $status->id], ['comments' => $comment]);
        $hiringRequest->save();

        $this->RegisterAction("El usuario ha dado por recibida una solicitud de contratacion", "high");
        return response(200);
    }

    public function getMyHiringRequests(Request $request)
    {
        $user = Auth::user();

        $hiringRequestIds = HiringRequestDetail::select('hiring_request_id')->distinct()->where('person_id', $user->person->id)->get();
        $hiringRequestIds = array_map(function ($item) {
            return $item['hiring_request_id'];
        }, $hiringRequestIds->toArray());

        $queryBuilder = HiringRequest::with(['school', 'contractType'])
            ->orderBy('created_at', 'DESC')
            ->whereIn('id', $hiringRequestIds);

        if ($request->query('active') == 'true') {
            $queryBuilder->where('request_status', '!=', HiringRequestStatusCode::GDC);
        }

        $hiringRequests = $queryBuilder->paginate($request->query('paginate'));
        $hiringRequests->makeHidden('status');

        $this->RegisterAction("El usuario ha consultado las solicitudes de  contratación que lo incluyen", "medium");
        return response($hiringRequests, 200);
    }

    public function MakeHiringRequestPDF()
    {
        //Se crea la fecha con el formato que se requiere para el pdf
        $date = Carbon::now()->locale('es');
        $fecha = "Ciudad Universitaria Dr. Fabio Castillo Figueroa, ".$date->day." de ".$date->monthName." de ".$date->year.".";
        $hiringRequest = HiringRequest::with('school')->with('contractType')->with('status')->findOrFail(151);
        $escuela = "Escuela de ".$hiringRequest->school->name;
        $pdf = PDF::loadView('hiringRequest.HiringRequest',compact('fecha','escuela','hiringRequest'));
        $this->RegisterAction("El usuario ha generado una solicitud de contratación en PDF", "high");
        return $pdf->download('solicitud_de_contratacion.pdf');
    }
}
