<?php

namespace App\Http\Controllers;

use App\Constants\ContractType;
use App\Constants\PersonValidationStatus;
use App\Http\Requests\UpdateSPNPRequestDetails;
use App\Http\Requests\UpdateTIRequestDetails;
use App\Http\Traits\WorklogTrait;
use App\Models\Activity;
use App\Models\Group;
use App\Models\HiringRequest;
use App\Models\HiringRequestDetail;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HiringRequestDetailController extends Controller
{
    use WorklogTrait;

    public function addSPNPRequestDetails($id, UpdateSPNPRequestDetails $request)
    {
        DB::beginTransaction();
        $validatedDetails = $request->validated();

        foreach ($validatedDetails as $detail) {
            $person = Person::findOrFail($detail['person_id']);
            if ($person->is_employee) {
                DB::rollBack();
                return response(['message' => 'Un empleado de la universidad no puede ser contratado por esta modalidad (SPNP)'], 400);
            } elseif ($person->status != PersonValidationStatus::Validado) {
                DB::rollBack();
                return response(['message' => 'No se han validado los datos de la persona con id' . $person->id], 400);
            }

            $totalWorkHours = array_reduce($detail['groups'], function ($pv, $group) {
                return $pv + $group['weekly_hours'];
            }, 0);
            if ($totalWorkHours > 40) {
                DB::rollBack();
                return response(['message' => 'El total de horas semanales a trabajar no puede ser mayor a 40 por persona'], 400);
            }
        }

        $user = Auth::user();
        $hiringRequest = HiringRequest::with(['contractType', 'school',])->findOrFail($id);
        $requestStatus = $hiringRequest->getLastStatusAttribute();
        if ($hiringRequest->contractType->name != ContractType::SPNP) {
            DB::rollBack();
            return response(['message' => 'Debe seleccionar un contrato del tipo "Contrato por Servicios Profesionales"'], 400);
        } elseif ($user->school->id != $hiringRequest->school->id) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion de otra escuela'], 400);
        } elseif ($requestStatus->order > 2) {
            DB::rollBack();
            return response(['message' => 'No puede agregar detalles a una solicitud de contratacion con estado: "' . $requestStatus->name . '"'], 400);
        }

        foreach ($validatedDetails as $detail) {
            $savedDetail = HiringRequestDetail::create($detail);

            foreach ($detail['activities'] as $activityName) {
                $activity = Activity::where('name', 'ilike', $activityName)->first();
                if (!$activity) {
                    $activity = Activity::create(['name' => $activityName]);
                }
                $activities[] = $activity;
            }
            $savedDetail->activities()->saveMany($activities);
            $savedDetail->activities = $activities;

            foreach ($detail['groups'] as $hiringGroup) {
                $group = Group::findOrFail($hiringGroup['id']);
                if ($group->people_id) {
                    DB::rollBack();
                    return response(['message' => 'El grupo con id ' . $group['id'] . ' ya tiene un docente asignado'], 400);
                }
                $group->people_id = $detail['person_id'];
                $group->save();
                $groups[] = $group;
            }
            $savedDetail->groups()->saveMany($groups);
            $savedDetail->groups = $groups;

            $savedDetails[] = $savedDetail;
        }

        $this->RegisterAction("El usuario ha actualizado la solicitud de contratación", "high");
        DB::commit();
        return response($savedDetails);
    }
}
