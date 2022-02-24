<?php

namespace App\Http\Controllers;

use App\Constants\HiringRequestStatusCode;
use App\Models\HiringRequest;
use App\Models\HiringRequestDetail;
use App\Models\Status;
use App\Models\StaySchedule;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use App\Http\Traits\WorklogTrait;
use App\Http\Traits\GeneratorTrait;
use App\Models\Agreement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $hiringRequests = HiringRequest::where('school_id', '=', $id)
            ->with(['school', 'contractType'])
            ->orderBy('code', 'DESC')
            ->paginate($request->query('paginate'));
        $hiringRequests->makeHidden('status');
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación", "medium");
        return response($hiringRequests, 200);
    }

    public function getAllHiringRequestsSecretary()
    {
        $hiringRequests = HiringRequest::whereIn('request_status', [HiringRequestStatusCode::EDS,HiringRequestStatusCode::RDS])->with('school')->with('contractType')->orderBy('created_at', 'DESC')->paginate(10);
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

    public function headerPDF($hiringRequest)
    {
        $date = Carbon::now()->locale('es');
        $fecha = "Ciudad Universitaria Dr. Fabio Castillo Figueroa, " . $date->day . " de " . $date->monthName . " de " . $date->year . ".";
        $fechaDetalle = $date->day . " de " . $date->monthName . " de " . $date->year . ".";
        if ($hiringRequest->school->id == 9) {
            $escuela =  $hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $hiringRequest->school->name;
        }
        return $detalle[] = (object)[
            'fecha' => $fecha,
            'fechaDetalle' => $fechaDetalle,
            'escuela' => $escuela
        ];
    }

    public function storeHiringRequest($id, $pdf)
    {
        $hiringRequest = HiringRequest::findOrFail($id);
        $hiringName = $hiringRequest->code . "-Solicitud.pdf";
        Storage::disk('hiringRequest')->put($hiringName, $pdf);
        $hiringRequest->fileName = $hiringName;
        $status = Status::whereIn('code', [HiringRequestStatusCode::FSC, HiringRequestStatusCode::EDS])->get();
        $hiringRequest->request_status = HiringRequestStatusCode::EDS;
        $hiringRequest->save();
        $hiringRequest->status()->attach($status);
        return ['message' => 'El archivo pdf ha sido guardado con exito ', 'success' => true];
    }

    public function MakeHiringRequestSPNP($id, $option)
    {

        //Primero se verifica el id y si la solicitud tiene almenos una persona aignada a la solicitud
        $hri = HiringRequest::findOrFail($id);
        if ($hri->details->count() == 0) {
            return response(['message' => 'La solicitud de contratacion no tiene detalles, por lo cual no se puede generar el pdf'], 400);
        };
        $hiringRequest = $this->show($hri->id);
        $header = $this->headerPDF($hiringRequest);
        $total = 0;

        foreach ($hiringRequest->details as $detail) {
            $subtotal = 0;
            $subtiempo = 0;
            $totalHoras = 0;
            foreach ($detail->hiringGroups as $group) {
                $subtotal += $group->hourly_rate * $group->work_weeks * $group->weekly_hours;
                $subtiempo += $group->weekly_hours * $group->work_weeks;
            }
            $detail->subtotal = $subtotal;
            $total += $subtotal;
            $totalHoras += $subtiempo;
            $detail->subtotalHoras = $totalHoras;
        }
        $hiringRequest->total = $total;

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
        //Ejemplo de como se hace merge de pdfs
        $m = new Merger();
        $pdf    = PDF::loadView('hiringRequest.HiringRequestSPNP', compact('header', 'hiringRequest'));
        $pdf2   = PDF::loadView('hiringRequest.HiringRequestSPNPDetails', compact('hiringRequest', 'header'));
        $pdf3   = PDF::loadView('hiringRequest.HiringRequestSPNPFunctions', compact('header', 'hiringRequest'));
        $pdf->setPaper('letter', 'portrait');
        $pdf2->setPaper('letter', 'landscape');
        $pdf3->setPaper('letter', 'portrait');
        $pdf->render();
        $pdf2->render();
        $pdf3->render();
        //NUMERAMOS LAS PAGINAS DE LOS DETALLES
        $dom_pdf    = $pdf2->getDomPDF();
        $canvas     = $dom_pdf->get_canvas();
        $canvas->page_text(670, 570, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //UNIMOS LOS PDFS
        $m->addRaw($pdf->output());
        $m->addRaw($pdf2->output());
        $m->addRaw($pdf3->output());
        $createdPdf = $m->merge();
        if ($option == "show") {
            $pdf = base64_encode($createdPdf);
            return response(['pdf' => $pdf], 200);
        } else {
            $resultado = $this->storeHiringRequest($hiringRequest->id, $createdPdf);
            return response($resultado, 201);
        }
    }

    public function MakeHiringRequestTiempoIntegral($id, $option)
    {
        $hri = HiringRequest::findOrFail($id);
        if ($hri->details->count() == 0) {
            return response(['message' => 'La solicitud de contratacion no tiene detalles, por lo cual no se puede generar el pdf'], 400);
        };
        $hiringRequest = $this->show($hri->id);
        $header = $this->headerPDF($hiringRequest);
        //Calculamos el total a pagar por persona

        foreach ($hiringRequest->details as $detail) {
            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $detail->total = $detail->work_months * $detail->monthly_salary * $detail->salary_percentage;
            $hiringRequest->total += $detail->total;
            foreach ($detail->activities as $act) {
                $mappedActivities[] = $act->name;
            }
            $detail->mappedActivities = $mappedActivities;
            //Obtenemos los horarios de permanencia y funciones en tiempo normal de la persona   
            $staySchedule = StaySchedule::where(['id' => $detail->stay_schedule_id])->with(['semester', 'scheduleDetails', 'scheduleActivities'])->firstOrFail();
            $hrStay = [];
            foreach ($staySchedule->scheduleDetails as $schedule) {
                array_push($hrStay, $horario = $schedule->day . " - " . date("g:ia", strtotime($schedule->start_time)) . ' a ' . date("g:ia", strtotime($schedule->finish_time)));
            }
            $hrAct = [];
            foreach ($staySchedule->scheduleActivities as $act) {
                array_push($hrAct, $act->name);
            }
            $detail->stayActivities = $hrAct;
            $detail->staySchedule =  $hrStay;
            //obtenemos los grupos de contratacion
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
                    "groupType" => $hg->group->grupo->name,
                    "number" => $hg->group->number,
                    'days' => $days,
                    'time' => $times,

                ];
            }
            $detail->mappedGroups = $mappedGroups;
        }
        $m = new Merger();
        $pdf    = PDF::loadView('hiringRequest.HiringRequestTI', compact('header', 'hiringRequest'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->render();
        $pdf2   = PDF::loadView('hiringRequest.HiringRequestTIDetails', compact('hiringRequest', 'header'));
        $pdf2->setPaper('letter', 'landscape');
        $pdf2->render();
        $dom_pdf    = $pdf2->getDomPDF();
        $canvas     = $dom_pdf->get_canvas();
        $canvas->page_text(670, 570, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //UNIMOS LOS PDFS
        $m->addRaw($pdf->output());
        $m->addRaw($pdf2->output());
        $createdPdf = $m->merge();
        if ($option == "show") {
            $pdf = base64_encode($createdPdf);
            return response(['pdf' => $pdf], 200);
        } else {
            $resultado = $this->storeHiringRequest($hiringRequest->id, $createdPdf);
            return response($resultado, 201);
        }
    }

    public function MakeHiringRequestTiempoAdicional($id, $option)
    {
        $hri = HiringRequest::findOrFail($id);
        if ($hri->details->count() == 0) {
            return response(['message' => 'La solicitud de contratacion no tiene detalles, por lo cual no se puede generar el pdf'], 400);
        };
        $hiringRequest = $this->show($hri->id);
        $header = $this->headerPDF($hiringRequest);
        //Calculamos el total a pagar por persona

        foreach ($hiringRequest->details as $detail) {

            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $detail->total = $detail->hourly_rate * $detail->work_weeks * $detail->weekly_hours;
            $hiringRequest->total += $detail->total;
            foreach ($detail->activities as $act) {
                $mappedActivities[] = $act->name;
            }
            $detail->mappedActivities = $mappedActivities;
            //Obtenemos los horarios de permanencia y funciones en tiempo normal de la persona   
            $staySchedule = StaySchedule::where(['id' => $detail->stay_schedule_id])->with(['semester', 'scheduleDetails', 'scheduleActivities'])->firstOrFail();
            $hrStay = [];
            foreach ($staySchedule->scheduleDetails as $schedule) {
                array_push($hrStay, $horario = $schedule->day . " - " . date("g:ia", strtotime($schedule->start_time)) . ' a ' . date("g:ia", strtotime($schedule->finish_time)));
            }
            $hrAct = [];
            foreach ($staySchedule->scheduleActivities as $act) {
                array_push($hrAct, $act->name);
            }
            $detail->stayActivities = $hrAct;
            $detail->staySchedule =  $hrStay;
            //obtenemos los grupos de contratacion
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
                    "groupType" => $hg->group->grupo->name,
                    "number" => $hg->group->number,
                    'days' => $days,
                    'time' => $times,

                ];
            }
            $detail->mappedGroups = $mappedGroups;
        }
        $m = new Merger();
        $pdf    = PDF::loadView('hiringRequest.HiringRequestTA', compact('header', 'hiringRequest'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->render();
        $pdf2   = PDF::loadView('hiringRequest.HiringRequestTADetails', compact('hiringRequest', 'header'));
        $pdf2->setPaper('letter', 'landscape');
        $pdf2->render();
        $dom_pdf    = $pdf2->getDomPDF();
        $canvas     = $dom_pdf->get_canvas();
        $canvas->page_text(670, 570, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //UNIMOS LOS PDFS
        $m->addRaw($pdf->output());
        $m->addRaw($pdf2->output());
        $createdPdf = $m->merge();

        if ($option == "show") {
            $pdf = base64_encode($createdPdf);
            return response(['pdf' => $pdf], 200);
        } else {
            $resultado = $this->storeHiringRequest($hiringRequest->id, $createdPdf);
            return response($resultado, 201);
        }
    }

    public function getPdf($id)
    {
        $hiringRequest = HiringRequest::findOrFail($id);
        if ($hiringRequest->fileName == null) {
            return response(['message' => 'No se ha generado el archivo pdf de la solicitud'], 400);
        }
        $pdf = base64_encode(Storage::disk('hiringRequest')->get($hiringRequest->fileName));
        return response(['pdf' => $pdf], 201);
    }

    public function getAllStatus()
    {
        $status = Status::all();
        return response($status, 200);
    }

    public function addAgreement($id, Request $request)
    {
        $fields = $request->validate([
            'code'      => 'required|string|unique:agreements',
            'approved'  => 'required|boolean',
            'agreed_on' => 'required|date',
            'file'      => 'required|mimes:pdf',
        ]);

        $hiringRequest = HiringRequest::findOrFail($id);

        // TODO: check if the request is in the required status
        if (false) {
            return response(['message' => 'La solicitud debe tener el estado <ESTADO> para poder agregar un acuerdo de Junta Directiva'], 400);
        }

        $file = $request->file('file');
        $fileName = 'acuerdo-' . $fields['code'] . '.pdf';
        Storage::disk('agreements')->put($fileName, File::get($file));

        Agreement::create([
            'hiring_request_id' => $hiringRequest->id,
            'code'              => $fields['code'],
            'approved'          => $fields['approved'],
            'agreed_on'         => $fields['agreed_on'],
            'file_uri'         => $fileName,
        ]);

        $status = Status::where('code', [HiringRequestStatusCode::RJD])->first();
        $hiringRequest->request_status = HiringRequestStatusCode::RJD;
        $hiringRequest->save();
        $hiringRequest->status()->attach(['status_id' => $status->id]);

        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene el acuerdo de junta directiva para la solicitud con id: " . $id, "high");
        return;
    }
}
