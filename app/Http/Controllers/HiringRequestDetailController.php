<?php

namespace App\Http\Controllers;

use App\Constants\ContractType;
use App\Constants\GroupStatus;
use App\Constants\HiringRequestStatusCode;
use App\Constants\PersonValidationStatus;
use App\Http\Requests\UpdateSPNPRequestDetails;
use App\Http\Requests\UpdateTARequestDetails;
use App\Http\Requests\UpdateTIRequestDetails;
use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use App\Models\ContractStatus;
use App\Models\DetailPositionActivity;
use App\Models\Group;
use App\Models\HiringGroup;
use App\Models\HiringRequest;
use App\Models\HiringRequestDetail;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Mail\ValidationDocsNotification;
use Mail;

class HiringRequestDetailController extends Controller
{
    use WorklogTrait;

    public function newCandidateNotification(HiringRequest $hiringRequest, $email, $type)
    {
        //Envio de correo de notificación que fue agregado un docente a la solicitud de contratación
        if ($hiringRequest->school->id == 9) {
            $escuela =  $hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $hiringRequest->school->name;
        }
        $mensajeEmail = "Ha sido agregado a la solicitud de contratación codigo <b>" . $hiringRequest->code . "</b> por <b> " . $type . "</b> en la <b>" . $hiringRequest->modality . " </b>de parte de la <b>" . $escuela . "</b> para ver más detalles de la solicitud y el estado de esta misma puede acceder al sistema con sus credenciales";
        try {
            Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'notificacionSolicitud'));
            $mensaje = 'Se envio el correo con exito';
        } catch (\Swift_TransportException $e) {
            $mensaje = 'No se envio el correo';
        }
    }

    public function addSPNPRequestDetails($id, UpdateSPNPRequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::findOrFail($validatedDetail['person_id']);
        if ($person->is_employee) {
            DB::rollBack();
            return response(['message' => 'Un empleado de la universidad no puede ser contratado por esta modalidad (SPNP)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id ' . $person->id], 400);
        }

        $totalWorkHours = array_reduce($validatedDetail['groups'], function ($pv, $group) {
            return $pv + $group['weekly_hours'];
        }, 0);
        if ($totalWorkHours > 40) {
            DB::rollBack();
            return response(['message' => 'El total de horas semanales a trabajar no puede ser mayor a 40 por persona'], 400);
        }

        $user = Auth::user();
        $hiringRequest = HiringRequest::with(['contractType', 'school',])->findOrFail($id);
        $requestStatus = $hiringRequest->getLastStatusAttribute();
        if ($hiringRequest->contractType->name != ContractType::SPNP) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::SPNP . '"'], 400);
        } elseif ($user->school->id != $hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $savedDetail = HiringRequestDetail::create(array_merge($validatedDetail, ['hiring_request_id' => $id]));

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $savedDetail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        foreach ($validatedDetail['groups'] as $hiringGroup) {
            $group = Group::findOrFail($hiringGroup['group_id']);
            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $group['id'] . ' ya tiene un docente asignado'], 400);
            }
            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();

            $savedHiringGroup = HiringGroup::create(array_merge($hiringGroup, ['hiring_request_detail_id' => $savedDetail->id]));

            $groups[] = $savedHiringGroup;
        }
        $savedDetail->groups = $groups;

        $this->RegisterAction("El usuario ha agregado a un docente a la solicitud de contratación con id: " . $id, "high");
        DB::commit();
        $this->newCandidateNotification($hiringRequest, $person->user->email, ContractType::SPNP);
        return response($savedDetail);
    }

    public function addTIRequestDetails($id, UpdateTIRequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::with(['employee', 'employee.staySchedules'])->findOrFail($validatedDetail['person_id']);
        // TODO: Verificar que tipo de empleado puede agregarse en solicitudes de tiempo integral
        if (false) {
            DB::rollBack();
            return response(['message' => 'Solo empleados del tipo <TIPO> pueden contratarse por esta modalidad (Tiempo Integral)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id' . $person->id], 400);
        }

        $savedDetail = HiringRequestDetail::create(array_merge($validatedDetail, ['hiring_request_id' => $id]));

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $savedDetail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        $weeklyHours = 0;
        foreach ($validatedDetail['group_ids'] as $groupId) {
            $group = Group::with('schedule')->findOrFail($groupId);
            array_reduce($group->schedule->toArray(), function ($pv, $schedule) {
                return $pv + (strtotime($schedule['finish_hour']) - strtotime($schedule['start_hour'])) / 3600;
            }, $weeklyHours);

            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $groupId . ' ya tiene un docente asignado'], 400);
            }
            $scheduleDetails = $person->employee->staySchedules->last()->scheduleDetails;

            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();
            $groups[] = $group;
        }
        if ($weeklyHours > 5) {
            DB::rollBack();
            return response(['message' => 'Un empleado no puede trabajar mas de 5 horas por semana en esta modalidad'], 400);
        }

        $savedDetail->groups()->saveMany($groups);
        $savedDetail->groups = $groups;

        $user = Auth::user();
        $hiringRequest = HiringRequest::with(['contractType', 'school',])->findOrFail($id);
        $requestStatus = $hiringRequest->getLastStatusAttribute();
        if ($hiringRequest->contractType->name != ContractType::TI) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::TI . '"'], 400);
        } elseif ($user->school->id != $hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $this->RegisterAction("El usuario ha agregado a un docente a la solicitud de contratación con id: " . $id, "high");
        DB::commit();
        //Envio de correo de notificación que fue agregado un docente a la solicitud de contratación
        $this->newCandidateNotification($hiringRequest, $person->user->email, ContractType::TI);
        return response($savedDetail);
    }

    public function addTARequestDetails($id, UpdateTARequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::with(['employee', 'employee.staySchedules'])->findOrFail($validatedDetail['person_id']);
        // TODO: Verificar que tipo de empleado puede agregarse en solicitudes de tiempo integral
        if (false) {
            DB::rollBack();
            return response(['message' => 'Solo empleados del tipo <TIPO> pueden contratarse por esta modalidad (Tiempo Adicional)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id' . $person->id], 400);
        }

        $savedDetail = HiringRequestDetail::create(array_merge($validatedDetail, ['hiring_request_id' => $id]));

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $savedDetail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        $weeklyHours = 0;
        foreach ($validatedDetail['group_ids'] as $groupId) {
            $group = Group::with('schedule')->findOrFail($groupId);
            array_reduce($group->schedule->toArray(), function ($pv, $schedule) {
                return $pv + (strtotime($schedule['finish_hour']) - strtotime($schedule['start_hour'])) / 3600;
            }, $weeklyHours);

            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $groupId . ' ya tiene un docente asignado'], 400);
            }
            $scheduleDetails = $person->employee->staySchedules->last()->scheduleDetails;

            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();
            $groups[] = $group;
        }
        if ($weeklyHours > 10) {
            DB::rollBack();
            return response(['message' => 'Un empleado no puede trabajar mas de 40 horas por mes en esta modalidad'], 400);
        }

        $savedDetail->groups()->saveMany($groups);
        $savedDetail->groups = $groups;

        $user = Auth::user();
        $hiringRequest = HiringRequest::with(['contractType', 'school',])->findOrFail($id);
        $requestStatus = $hiringRequest->getLastStatusAttribute();
        if ($hiringRequest->contractType->name != ContractType::TA) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::TA . '"'], 400);
        } elseif ($user->school->id != $hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $this->RegisterAction("El usuario ha agregado a un docente a la solicitud de contratación con id: " . $id, "high");
        DB::commit();
        //Envio de correo de notificación que fue agregado un docente a la solicitud de contratación
        $this->newCandidateNotification($hiringRequest, $person->user->email, ContractType::TA);
        return response($savedDetail);
    }

    public function getRequestDetails($id)
    {
        $requestDetails = HiringRequestDetail::with([
            'person',
            'hiringGroups',
            'positionActivity.activities',
            'positionActivity.position',
        ])->findOrFail($id);

        $user = Auth::user();

        if ($user->school_id != $requestDetails->hiringRequest->school_id) {
            return response(['message' => 'No puede ver solicitudes de contratacion de otra escuela'], 400);
        }

        $this->RegisterAction("El usuario ha consultado un detalle de la solicitud de contratación con id: " . $id, "medium");
        return response($requestDetails);
    }

    public function deleteRequestDetails($id)
    {
        $requestDetail = HiringRequestDetail::with('hiringRequest')->findOrFail($id);

        $requestStatus = $requestDetail->hiringRequest->getLastStatusAttribute();
        $user = Auth::user();

        if ($user->school_id != $requestDetail->hiringRequest->school_id) {
            return response(['message' => 'No puede editar solicitudes de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            return response(['message' => 'No puede eliminar detalles de una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        foreach ($requestDetail->groups as $group) {
            $group->people_id = null;
            $group->status = GroupStatus::SDA;
            $group->save();
        }

        $requestDetail->delete();
        $this->RegisterAction("El usuario ha eliminado a un docente a la solicitud de contratación con id: " . $id, "high");
        return response(null, 204);
    }

    public function updateSPNPRequestDetail($id, UpdateSPNPRequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::findOrFail($validatedDetail['person_id']);
        if ($person->is_employee) {
            DB::rollBack();
            return response(['message' => 'Un empleado de la universidad no puede ser contratado por esta modalidad (SPNP)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id ' . $person->id], 400);
        }

        $totalWorkHours = array_reduce($validatedDetail['groups'], function ($pv, $group) {
            return $pv + $group['weekly_hours'];
        }, 0);
        if ($totalWorkHours > 40) {
            DB::rollBack();
            return response(['message' => 'El total de horas semanales a trabajar no puede ser mayor a 40 por persona'], 400);
        }

        $detail = HiringRequestDetail::with([
            'hiringRequest',
            'hiringRequest.contractType',
            'hiringRequest.school',
        ])->findOrFail($id);
        $user = Auth::user();
        $requestStatus = $detail->hiringRequest->getLastStatusAttribute();

        if ($detail->hiringRequest->contractType->name != ContractType::SPNP) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::SPNP . '"'], 400);
        } elseif ($user->school->id != $detail->hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede editar detalles de una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede editar detalles de una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $hiringGroups = $detail->groups;
        $groupIds = array_map(function ($hGroup) {
            return $hGroup['id'];
        }, $hiringGroups->toArray());

        $groups = Group::whereIn('id', $groupIds)->get();
        foreach ($groups as $group) {
            $group->status = GroupStatus::SDA;
            $group->people_id = null;
            $group->save();
        }

        $detail->groups()->detach();
        $detail->positionActivity()->delete();
        $detail->update($validatedDetail);

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $detail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        foreach ($validatedDetail['groups'] as $hiringGroup) {
            $group = Group::findOrFail($hiringGroup['group_id']);
            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $group['id'] . ' ya tiene un docente asignado'], 400);
            }
            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();

            $savedHiringGroup = HiringGroup::create(array_merge($hiringGroup, ['hiring_request_detail_id' => $detail->id]));

            $newGroups[] = $savedHiringGroup;
        }
        $detail->groups = $newGroups;


        $this->RegisterAction("El usuario ha actualizado el detalle de la solicitud de contratación SPNP con id: " . $detail->id, "high");
        DB::commit();
        return response($detail);
    }

    public function updateTIRequestDetails($id, UpdateTIRequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::with(['employee', 'employee.staySchedules'])->findOrFail($validatedDetail['person_id']);
        // TODO: Verificar que tipo de empleado puede agregarse en solicitudes de tiempo integral
        if (false) {
            DB::rollBack();
            return response(['message' => 'Solo empleados del tipo <TIPO> pueden contratarse por esta modalidad (Tiempo Integral)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id' . $person->id], 400);
        }

        $detail = HiringRequestDetail::with([
            'hiringRequest',
            'hiringRequest.contractType',
            'hiringRequest.school',
        ])->findOrFail($id);

        $user = Auth::user();
        $requestStatus = $detail->hiringRequest->getLastStatusAttribute();
        if ($detail->hiringRequest->contractType->name != ContractType::TI) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::TI . '"'], 400);
        } elseif ($user->school->id != $detail->hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede editar detalles de una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede editar detalles de una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $hiringGroups = $detail->groups;
        $groupIds = array_map(function ($hGroup) {
            return $hGroup['id'];
        }, $hiringGroups->toArray());

        $groups = Group::whereIn('id', $groupIds)->get();
        foreach ($groups as $group) {
            $group->status = GroupStatus::SDA;
            $group->people_id = null;
            $group->save();
        }

        $detail->groups()->detach();
        $detail->positionActivity()->delete();
        $detail->update($validatedDetail);

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $detail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        $weeklyHours = 0;
        foreach ($validatedDetail['group_ids'] as $groupId) {
            $group = Group::with('schedule')->findOrFail($groupId);
            array_reduce($group->schedule->toArray(), function ($pv, $schedule) {
                return $pv + (strtotime($schedule['finish_hour']) - strtotime($schedule['start_hour'])) / 3600;
            }, $weeklyHours);

            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $groupId . ' ya tiene un docente asignado'], 400);
            }
            $scheduleDetails = $person->employee->staySchedules->last()->scheduleDetails;

            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();
            $grp[] = $group;
        }
        if ($weeklyHours > 5) {
            DB::rollBack();
            return response(['message' => 'Un empleado no puede trabajar mas de 5 horas por semana en esta modalidad'], 400);
        }

        $detail->groups()->saveMany($grp);
        $detail->groups = $groups;

        $this->RegisterAction("El usuario ha actualizado el detalle de la solicitud de contratación TI con id: " . $detail->id, "high");
        DB::commit();
        return response($detail);
    }

    public function updateTARequestDetails($id, UpdateTARequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetail = $request->validated();

        $person = Person::with(['employee', 'employee.staySchedules'])->findOrFail($validatedDetail['person_id']);
        // TODO: Verificar que tipo de empleado puede agregarse en solicitudes de tiempo integral
        if (false) {
            DB::rollBack();
            return response(['message' => 'Solo empleados del tipo <TIPO> pueden contratarse por esta modalidad (Tiempo Adicional)'], 400);
        } elseif ($person->status != PersonValidationStatus::Validado) {
            DB::rollBack();
            return response(['message' => 'No se han validado los datos de la persona con id' . $person->id], 400);
        }

        $detail = HiringRequestDetail::with([
            'hiringRequest',
            'hiringRequest.contractType',
            'hiringRequest.school',
        ])->findOrFail($id);

        $user = Auth::user();
        $requestStatus =  $detail->hiringRequest->getLastStatusAttribute();
        if ($detail->hiringRequest->contractType->name != ContractType::TA) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "' . ContractType::TA . '"'], 400);
        } elseif ($user->school->id != $detail->hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        $hiringGroups = $detail->groups;
        $groupIds = array_map(function ($hGroup) {
            return $hGroup['id'];
        }, $hiringGroups->toArray());

        $groups = Group::whereIn('id', $groupIds)->get();
        foreach ($groups as $group) {
            $group->status = GroupStatus::SDA;
            $group->people_id = null;
            $group->save();
        }

        $detail->groups()->detach();
        $detail->positionActivity()->delete();
        $detail->update($validatedDetail);

        foreach ($validatedDetail['position_activities'] as $positionActivities) {
            $newPositionActivities = DetailPositionActivity::create([
                'position_id'               => $positionActivities['position_id'],
                'hiring_request_detail_id'  => $detail->id,
            ]);

            $activities = [];
            foreach ($positionActivities['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $newPositionActivities->activities()->saveMany($activities);
        }

        $weeklyHours = 0;
        foreach ($validatedDetail['group_ids'] as $groupId) {
            $group = Group::with('schedule')->findOrFail($groupId);
            array_reduce($group->schedule->toArray(), function ($pv, $schedule) {
                return $pv + (strtotime($schedule['finish_hour']) - strtotime($schedule['start_hour'])) / 3600;
            }, $weeklyHours);

            if ($group->status != GroupStatus::SDA) {
                DB::rollBack();
                return response(['message' => 'El grupo con id ' . $groupId . ' ya tiene un docente asignado'], 400);
            }
            $scheduleDetails = $person->employee->staySchedules->last()->scheduleDetails;

            $group->people_id = $validatedDetail['person_id'];
            $group->status = GroupStatus::DASC;
            $group->save();
            $grp[] = $group;
        }
        if ($weeklyHours > 10) {
            DB::rollBack();
            return response(['message' => 'Un empleado no puede trabajar mas de 40 horas por mes en esta modalidad'], 400);
        }

        $detail->groups()->saveMany($grp);
        $detail->groups = $groups;
        $this->RegisterAction("El usuario ha actualizado el detalle de la solicitud de contratación TA con id: " . $id, "high");
        DB::commit();
        return response($detail);
    }

    public function addRequestDetailPdf($id, Request $request)
    {
        $request->validate(['schedule' => 'required|mimes:pdf']);
        $requestDetail = HiringRequestDetail::with('hiringRequest')->findOrFail($id);

        $requestStatus = $requestDetail->hiringRequest->getLastStatusAttribute();
        if ($requestStatus->order > 2) {
            return response(['message' => 'No puede editar una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        if ($requestDetail->schedule_file != null) {
            Storage::disk('requestDetailSchedules')->delete($requestDetail->schedule_file);
        }

        $file = $request->file('schedule');
        $fileName = 'horarioDetalleContratacion' . $id . '.pdf';
        Storage::disk('requestDetailSchedules')->put($fileName, File::get($file));

        $requestDetail->schedule_file = $fileName;
        $requestDetail->save();

        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene el horario del detalle de contratacion " . $id, "high");
        return;
    }

    public function getContractHistory($id)
    {
        $hiringRequest = HiringRequestDetail::with('contractStatus')->findOrFail($id);
        if ($hiringRequest->request_status == HiringRequestStatusCode::GDC) {
            return response(['message' => 'El contrato no existe'], 404);
        }

        $contractHistory = $hiringRequest->contractStatus;
        $this->regsiterAction('El usuario ha consultado el historial del contrato ' . $id, 'medium');
        return $contractHistory;
    }

    public function updateContractHistory($id, Request $request)
    {
        // Agregar fecha manualmente 
        $fields = $request->validate([
            'code' => 'required|string'
        ]);
        $detail = HiringRequestDetail::with(['hiringRequest', 'contractStatus'])->findOrFail($id);
        if ($detail->request_status == HiringRequestStatusCode::GDC) {
            return response(['message' => 'El contrato no existe'], 404);
        }

        $contractStatus = ContractStatus::where('code', $fields['code'])->findOrFail();

        $detail->contractStatus()->attach(['contract_status_id' => $contractStatus->id]);
        $this->regsiterAction('El usuario ha actualizado el estado del contrato ' . $id . ' a ' . $fields['code'], 'medium');
        return $detail->contractStatus();
    }
}
