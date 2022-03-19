<?php

namespace App\Http\Controllers;

use App\Constants\ContractStatusCode;
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
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Mail\ValidationDocsNotification;
use App\Models\ContractStatus;
use Mail;

class HiringRequestController extends Controller
{
    use WorklogTrait, GeneratorTrait;

    public function store(StoreHiringRequestRequest $request)
    {
        $newHiringRequest = HiringRequest::create(array_merge($request->all(), [
            'code'   => $this->generateRequestCode($request->school_id),
            'request_status' => HiringRequestStatusCode::RDC,
        ]));
        $status = Status::whereIn('code', [HiringRequestStatusCode::CSC, HiringRequestStatusCode::RDC])->orderBy('order')->get();
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
            'details.positionActivity.position.activities',
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
            'details.contractStatus',
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

    public function getAllHiringRequestsHR()
    {
        return  $hiringRequests = HiringRequest::with(['school', 'contractType'])
            ->where('request_status', '=', HiringRequestStatusCode::ERH)
            ->where(function ($query) {
                $query->whereNull('validated')
                    ->orWhere('validated', false);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratacion enviadas a Recursos Humanos", "medium");
        return response($hiringRequests, 200);
    }

    public function getAllHiringRequestsSecretary(Request $request)
    {
        $status = mb_strtoupper($request->status, 'UTF-8');
        if (!(!$status || $status == HiringRequestStatusCode::ESD || $status == HiringRequestStatusCode::RDS)) {
            return response(['message' => 'Solo puede filtrar por estatus ESD o RDS']);
        }
        $hiringRequestsQB = HiringRequest::whereIn('request_status', [HiringRequestStatusCode::ESD, HiringRequestStatusCode::RDS])
            ->with('school')
            ->with('contractType')
            ->orderBy('request_status', 'ASC')
            ->orderBy('updated_at', 'ASC');

        if ($status != null) {
            $hiringRequestsQB->where('request_status', $status);
        }

        $hiringRequests = $hiringRequestsQB->paginate(10);
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratacion enviadas a secretaria", "medium");
        return response($hiringRequests, 200);
    }


    public function secretaryReceptionHiringRequest(HiringRequest $hiringRequest)
    {
        if ($hiringRequest->validated != true || $hiringRequest->request_status != HiringRequestStatusCode::ESD) {
            return response(['message' => 'Solo las solicitudes validadas por Recursos Humanos pueden ser dadas por recibidas por secretaria'], 400);
        }

        $status = Status::where('code', HiringRequestStatusCode::RDS)->first();
        $comment = 'La solicitud fue aceptada por secretaria y pasara a ser agendada para ser vista en junta directiva';
        $hiringRequest->status()->attach(['status_id' => $status->id], ['comments' => $comment]);
        $hiringRequest->request_status = HiringRequestStatusCode::RDS;
        $hiringRequest->save();
        $emails = $this->getDirectorEmail($hiringRequest->school_id);
        $mensajeEmail = " Se ha dado por recibida la solicitud de contratación con código <b>: " . $hiringRequest->code . "</b> por parte de la secretaría y ha sido agendada para ser vista en Junta Directiva";

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'recepcionSecretary'));
                $mensaje = 'Se ha dado por recibida la solicitud de contratación y se ha enviado el correo notificando al director';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'Se ha dado por recibida la solicitud de contratación pero no se ha enviado el correo notificando al director';
            }
        }
        $this->RegisterAction('El usuario ha dado por recibida una solicitud de contratación', 'high');
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

    public function getHRMail()
    {
        $role = Role::where('name', 'Recursos Humanos')->first();
        $rrhh = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->get()->toArray();
        return $rrhh;
    }

    public function sendToHR(HiringRequest $hiringRequest)
    {
        $emails =  $this->getHRMail();
        $status = Status::whereIn('code', [HiringRequestStatusCode::FSC, HiringRequestStatusCode::ERH])->get();
        $hiringRequest->request_status = HiringRequestStatusCode::ERH;
        $hiringRequest->save();
        $hiringRequest->status()->attach($status);
        if ($hiringRequest->school->id == 9) {
            $escuela =  $hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $hiringRequest->school->name;
        }
        $mensajeEmail = "Se ha solicitado la validación de la solicitud de contratación con codigo <b>" . $hiringRequest->code . "</b> de parte de <b>" . $escuela . "</b>.
        <ul>
            <li>Código: <b>" . $hiringRequest->code . "</b> </li>
            <li>Tipo de Solicitud de Contrato:<b> " . $hiringRequest->contractType->name . " </b>  </li>
            <li>Modalidad: <b>" . $hiringRequest->modality . "</b>   </li>
        </ul><br>
     ";

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'validationHR'));
                $mensaje = 'La solicitud se ha enviado a RRHH para validación y se envió el correo de notificación al responsable de RRHH';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'La solicitud se ha enviado a RRHH para validación, pero no se envió el correo de notificación al responsable de RRHH';
            }
        }
        $this->registerAction('La solicitud se ha enviado a RRHH para validacion', 'high');
        return [['message' => $mensaje], 'success' => true];
    }

    public function getDirectorEmail($id){
        $role = Role::where('name', 'Director Escuela')->first();
        $director = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->where('users.school_id',"=",$id)->get()->toArray();
        return $director;
    }

    public function getAsistenteEmail(){
        $role = Role::where('name', 'Asistente Administrativo')->first();
        $asistente = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->get()->toArray();
        return $asistente;
    }

    public function reviewHR(HiringRequest $hiringRequest, Request $request)
    {
        $validatedRequest = $request->validate([
            'validated' => 'required|boolean',
            'comments'  => 'required|string',
        ]);
        $emails = $this->getDirectorEmail($hiringRequest->school_id);

        $hiringRequest->update($validatedRequest);

        if ($validatedRequest['validated'] == true) {
            $this->registerAction('El usuario valido que la solicitud si cumple con las validaciones', 'high');
            $mensajeEmail = "Se ha validado la solicitud de contratación con código <b>" . $hiringRequest->code . "</b> que fue enviada para su respectiva validación por parte del Recursos Humanos, la solicitud de contrato ha sido validada y habilitada para poder ser enviada a Secretaría de Decanato. ";
        } else {
            $this->registerAction('El usuario reviso la solicitud y publicó observaciones de cambios a hacer', 'high');
            $mensajeEmail = "Se ha revisado la solicitud de contratación con código <b>" . $hiringRequest->code . "</b> por parte de Recursos Humanos, la solicitud de contrato ha sido observada para poder ser modificada según las observaciones pertinentes. ";
        }
       
       

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'validationHRDirector'));
                $mensaje = 'Se envió el correo con éxito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envió el correo';
            }
        }
        return $hiringRequest;
    }

    public function storeHiringRequest($id, $pdf)
    {
        $hiringRequest = HiringRequest::findOrFail($id);
        $hiringName = $hiringRequest->code . "-Solicitud.pdf";
        Storage::disk('hiringRequest')->put($hiringName, $pdf);
        $hiringRequest->fileName = $hiringName;
        $status = Status::whereIn('code', [HiringRequestStatusCode::ESD])->get();
        $hiringRequest->request_status = HiringRequestStatusCode::ESD;
        $hiringRequest->save();
        $hiringRequest->status()->attach($status);
        $emails = $this->getAsistenteEmail();
        if ($hiringRequest->school->id == 9) {
            $escuela =  $hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $hiringRequest->school->name;
        }
        $mensajeEmail = "Se ha enviado la solicitud de contratación validada con código <b>" . $hiringRequest->code . "</b> de parte de la  <b>" . $escuela . "</b> para que esta sea recibida para ser agendada para en Junta Directiva.
         <ul>
            <li>Código: <b>" . $hiringRequest->code . "</b> </li>
            <li>Tipo de Solicitud de Contrato:<b> " . $hiringRequest->contractType->name . " </b>  </li>
            <li>Modalidad: <b>" . $hiringRequest->modality . "</b>   </li>
        </ul>";

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'SendSecretarySolicitude'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            }
        }
        return ['message' => 'El archivo pdf ha sido guardado con éxito ', 'success' => true];
    }

    public function MakeHiringRequestSPNP($id, $option)
    {
        //Primero se verifica el id y si la solicitud tiene almenos una persona asignada a la solicitud
        $hri = HiringRequest::findOrFail($id);
        if ($hri->details->count() == 0) {
            return response(['message' => 'La solicitud de contratación no tiene detalles, no se puede generar el pdf'], 400);
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
            $detail->period = date('d/m/Y', strtotime($detail->start_date)) . " - " . date('d/m/Y', strtotime($detail->finish_date));
            $positionActivities = [];
            foreach ($detail->positionActivity as $position) {

                $Activities = [];
                foreach ($position->position->activities as $activity) {
                    $Activities[] = $activity->name;
                }
                $positionActivities[] = ['position' => $position->position->name, 'activities' => $Activities];
            }
            $detail->positionActivities = $positionActivities;
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
        //Agregamos los pdfs con los documentos de los candidatos
        foreach ($hiringRequest->details as $detail) {
            $m->addRaw(\Storage::disk('personDocsMerged')->get($detail->person->merged_docs));
        }

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
            return response(['message' => 'La solicitud de contratación no tiene detalles, por lo cual no se puede generar el pdf'], 400);
        };
        $hiringRequest = $this->show($hri->id);
        $header = $this->headerPDF($hiringRequest);
        //Calculamos el total a pagar por persona

        foreach ($hiringRequest->details as $detail) {
            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $detail->total = $detail->work_months * $detail->monthly_salary * $detail->salary_percentage;
            $hiringRequest->total += $detail->total;
            $positionActivities = [];
            foreach ($detail->positionActivity as $position) {

                $Activities = [];
                foreach ($position->position->activities as $activity) {
                    $Activities[] = $activity->name;
                }
                $positionActivities[] = ['position' => $position->position->name, 'activities' => $Activities];
            }
            $detail->positionActivities = $positionActivities;
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
        foreach ($hiringRequest->details as $detail) {
            $m->addRaw(\Storage::disk('personDocsMerged')->get($detail->person->merged_docs));
        }
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
            return response(['message' => 'La solicitud de contratación no tiene detalles, por lo cual no se puede generar el pdf'], 400);
        };
        $hiringRequest = $this->show($hri->id);
        $header = $this->headerPDF($hiringRequest);
        //Calculamos el total a pagar por persona

        foreach ($hiringRequest->details as $detail) {

            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $detail->total = $detail->hourly_rate * $detail->work_weeks * $detail->weekly_hours;
            $hiringRequest->total += $detail->total;
            $positionActivities = [];
            foreach ($detail->positionActivity as $position) {

                $Activities = [];
                foreach ($position->position->activities as $activity) {
                    $Activities[] = $activity->name;
                }
                $positionActivities[] = ['position' => $position->position->name, 'activities' => $Activities];
            }
            $detail->positionActivities = $positionActivities;
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
        foreach ($hiringRequest->details as $detail) {
            $m->addRaw(\Storage::disk('personDocsMerged')->get($detail->person->merged_docs));
        }
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
        $status = Status::orderBy('order', 'ASC')->get();
        return response($status, 200);
    }

    public function getAllContractStatus()
    {
        $status = ContractStatus::orderBy('order', 'ASC')->get();
        return response($status, 200);
    }

    public function addAgreement($id, Request $request)
    {
        $fields = $request->validate([
            'code'      => 'required|string|unique:agreements',
            'approved'  => 'required|boolean',
            'agreed_on' => 'required|date|before_or_equal:today',
            'file'      => 'required|mimes:pdf',
        ]);

        $hiringRequest = HiringRequest::findOrFail($id);

        if ($hiringRequest->request_status != HiringRequestStatusCode::RDS) {
            return response(['message' => 'La solicitud debe haber sido recibida en Secretaría de Decanato para poder agregar un acuerdo de Junta Directiva'], 400);
        }

        $file = $request->file('file');
        $fileName = str_replace('/', '-', 'acuerdo-' . $fields['code'] . '.pdf');
        Storage::disk('agreements')->put($fileName, File::get($file));

        Agreement::create([
            'hiring_request_id' => $hiringRequest->id,
            'code'              => $fields['code'],
            'approved'          => $fields['approved'],
            'agreed_on'         => $fields['agreed_on'],
            'file_uri'         => $fileName,
        ]);

        if ($fields['approved'] == true) {
            $status = Status::whereIn('code', [HiringRequestStatusCode::RJD, HiringRequestStatusCode::GDC])->get();
            $hiringRequest->request_status = HiringRequestStatusCode::GDC;
            $hiringRequest->save();
            $hiringRequest->status()->attach(['status_id' => $status[0]->id]);
            $hiringRequest->status()->attach(['status_id' => $status[1]->id]);

            $contractStatus = ContractStatus::where('code', ContractStatusCode::ELB)->first();
            foreach ($hiringRequest->details as $detail) {
                // Enviar correo a cada contratado que ya hay contrato
                $detail->contractStatus()->attach(['contract_status_id' => $contractStatus->id]);
            }
            $this->sendAgrementMail($hiringRequest);
        } else {
            $status = Status::where('code', [HiringRequestStatusCode::RJD])->first();
            $hiringRequest->request_status = HiringRequestStatusCode::RJD;
            $hiringRequest->save();
            $hiringRequest->status()->attach(['status_id' => $status->id]);
        }

        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene el acuerdo de Junta Directiva para la solicitud con id: " . $id, "high");
        return;
    }

    public function getCandidatesEmail($id){
        $hiringRequest = HiringRequest::findOrFail($id);
        $candidates = [];
        foreach ($hiringRequest->details as $detail) {
         
                array_push($candidates, $detail->person->user->email);
        }
        return $candidates;
    }

    public function sendAgrementMail(HiringRequest $hiringRequest){
        $emailDirector = $this->getDirectorEmail($hiringRequest->school_id);
        $emailCadidates = $this->getCandidatesEmail($hiringRequest->id);
        $emailHR = $this->getHRmail();
        //Notificando al director
        foreach ($emailDirector as $email) {
            $mensajeEmail = "Se ha aprobado y agregado el acuerdo de Junta Directiva para la solicitud con código: " . $hiringRequest->code ." Ya puede ver el acuerdo de Junta Directiva en los detalles de la solicitud de contratación"; ;
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'AgreementUpdate'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            }
        }
        //Notificando a los candidatos
        foreach ($emailCadidates as $email) {
            $mensajeEmail = "Se ha aprobado y agregado el acuerdo de Junta Directiva para la solicitud con Codigo: " . $hiringRequest->code ." Ya puede ver el detalle de su contración y ver los paso siguientes a la generación de su contrato."; ;
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'AgreementUpdate'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            }
        }
        //Notificando a HR para generar contrato
        foreach ($emailHR as $email) {
            $mensajeEmail = "Se ha Aprobado y Agregado el acuerdo de junta directiva para la solicitud de contratacion con Codigo: " . $hiringRequest->code ." Ya puede ver el detalle del acuerdo de Junta directiva y se ha habilitado la generación de los contratos para los candidatos involucrados en la solicitud de contratación";
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'AgreementUpdate'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            }
        }

    }

    public function getAgreements($id)
    {
        $hiringRequest = HiringRequest::with('agreement')->findOrFail($id);
        $agreement = Agreement::findOrFail($hiringRequest->agreement->id);
        $pdf = base64_encode(Storage::disk('agreements')->get($agreement->file_uri));
        return response([
            "agreement" => $agreement,
            "pdf" => $pdf
        ], 200);
    }

    public function hiringRequestRRHH()
    {
        //NOMBRE VA VARIAS MAS ADELANTE YA QUE ESTE ENDPOINT SOLO TRAE LAS SOLICITUDES LISTAS PARA GENERAR CONTRATOS
        $hiringRequests = HiringRequest::whereIn('request_status', [HiringRequestStatusCode::RJD, HiringRequestStatusCode::GDC])->with('school')->with('contractType')->orderBy('created_at', 'DESC')->paginate(10);
        $this->RegisterAction("El usuario ha consultado todas las solicitudes de contratación a las cuales se les puede generar contrato", "medium");
        return response($hiringRequests, 200);
    }
}
