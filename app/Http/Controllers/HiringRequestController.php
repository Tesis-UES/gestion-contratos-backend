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
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;
use iio\libmergepdf\Merger;

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
        $relations = [
            'school',
            'contractType',
            'status',
            'details',
            'details.staySchedule',
            'details.staySchedule.semester',
            'details.staySchedule.scheduleDetails',
            'details.staySchedule.scheduleActivities',
            'details.groups',
            'details.groups.course',
            'details.groups.schedule',
            'details.groups.grupo',
            'details.groups',
            'details.hiringGroups',
            'details.activities',
            'details.person',
            'details.person.employee',
            'details.person.employee.escalafon',
            'details.person.employee.employeeTypes',
        ];
        $hiringRequest = HiringRequest::with($relations)->findOrFail($id);

        return $hiringRequest;
    }

    public function showBase($id)
    {
        $relations = [
            'school',
            'contractType',
            'status',
            'details',
            'details.person',
        ];
        $hiringRequest = HiringRequest::with($relations)->findOrFail($id);

        $this->registerAction('El usuario ha consultado los detalles base de la solicitud de contratacion con id: ' . $id, 'medium');
        return response($hiringRequest, 200);
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
        $fecha = "Ciudad Universitaria Dr. Fabio Castillo Figueroa, " . $date->day . " de " . $date->monthName . " de " . $date->year . ".";
        $hiringRequest = $this->show(51);
        $escuela = "Escuela de " . $hiringRequest->school->name;
        $total = 0;

        foreach ($hiringRequest->details as $detail) {
            $subtotal = 0;
            foreach ($detail->hiringGroups as $group) {
                $subtotal += $group->hourly_rate * $group->work_weeks * $group->weekly_hours;
            }
            $detail->subtotal = $subtotal;
            $total += $subtotal;
        }
        $hiringRequest->total = $total;
        $pdf = PDF::loadView('hiringRequest.HiringRequestSPNP', compact('fecha', 'escuela', 'hiringRequest'));
        $this->RegisterAction("El usuario ha generado una solicitud de contratación en PDF", "high");
        //return date("g:ia", strtotime( $hiringRequest->details[0]->hiringGroups[0]->group->schedule[0]->start_hour));
        // return $hiringRequest->details;
        foreach ($hiringRequest->details as $detail) {

            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $detail->period = $detail->start_date . "-" . $detail->finish_date;

            $mappedActivities = [];
            foreach ($detail->activities as $act) {
                $mappedActivities[] = $act->name;
            }
            $detail->mappedActivities = $mappedActivities;

            $mappedGroups = [];
            foreach ($detail->hiringGroups as $hg) {
                $days = [];
                $times = [];
                foreach ($hg->group->schedule as $schedule) {
                    array_push($days, $schedule->day);
                    $horario = date("g:ia", strtotime($schedule->start_hour)) . '-' . date("g:ia", strtotime($schedule->finish_hour));
                    if (!in_array($horario, $times)) array_push($times, $horario);
                }

                $times = implode($times);

                if (sizeof($days) == 2) {
                    $days = implode(' y ', $days);
                } else {
                    $days = implode(',', $days);
                }


                $mappedGroups[] = (object)[
                    "name" => $hg->group->course->name,
                    "groupType" => $hg->group->grupo->name . "-" . $hg->group->number,
                    'days' => $days,
                    'time' => $times,
                    "hourly_rate" => $hg->hourly_rate,
                    "weekly_hours" => $hg->weekly_hours,
                    "work_weeks" => $hg->work_weeks,
                ];
            }
            $detail->mappedGroups = $mappedGroups;
        }
        /* 
        $pdf2 = PDF::loadView('hiringRequest.HiringRequestSPNPDetails', compact('hiringRequest'));
        $pdf2->setPaper('A4', 'landscape');
        return $pdf2->download('solicitud_de_contratacion.pdf'); */


        //Ejemplo de como se hace merge de pdfs
        $m = new Merger();
        $pdf = PDF::loadView('hiringRequest.HiringRequestSPNP', compact('fecha', 'escuela', 'hiringRequest'));
        $pdf2 = PDF::loadView('hiringRequest.HiringRequestSPNPDetails', compact('hiringRequest'));
        $pdf2->setPaper('A4', 'landscape');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdf2->render();
        $m->addRaw($pdf->output());
        $m->addRaw($pdf2->output());
        $createdPdf = $m->merge();

        return response($createdPdf, 200)->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'inline; filename="Solicitud de contratación.pdf"');
    }
    public function getAllStatus()
    {
        $status = Status::all();
        return response($status, 200);
    }
}
