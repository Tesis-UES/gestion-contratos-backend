<?php

namespace App\Http\Controllers;

use App\Constants\ContractType;
use App\Models\Bank;
use App\Models\Format;
use App\Models\EmployeeType;
use App\Models\Escalafon;
use App\Models\Faculty;
use App\Models\Person;
use App\Models\PersonValidation;
use App\Models\{PersonChange, CentralAuthority, StaySchedule};
use Illuminate\Http\Request;
use App\Http\Traits\{WorklogTrait, ValidationTrait};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Luecano\NumeroALetras\NumeroALetras;
use DB;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use App\Http\Controllers\PersonController;
use App\Models\HiringRequest;
use App\Models\HiringRequestDetail;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class ContractController extends Controller
{
    use WorklogTrait;

    public function getPrincipalinfo()
    {
        $task = new PersonController;
        $date = Carbon::now()->locale('es');
        //Datos Generales del Rector Requeridos en todo los contratos
        $formatter = new NumeroALetras();
        $rector = CentralAuthority::where('position', '=', 'Rector')->where('status', 'true')->get()->first();
        $edad = $task->calculaedad($rector->birth_date);
        $nombreRector = "" . $rector->firstName . " " . $rector->middleName . " " . $rector->lastName . "";
        $firmaRector = $rector->reading_signature;
        $edadRector = $formatter->toString($edad);
        $profesionRector = $rector->profession;
        $duiTextoRector = $rector->text_dui;
        $nitTextoRector = $rector->text_nit;
        $fecha = "A LOS  " . $formatter->toString($date->day) . " DIAS DEL MES DE  " . $date->monthName . " DE " . $formatter->toString($date->year) . "";
        return [
            'nombreRector' => $nombreRector,
            'firmaRector' => $firmaRector,
            'edadRector' => $edadRector,
            'profesionRector' => $profesionRector,
            'duiRector' => $duiTextoRector,
            'nitRector' => $nitTextoRector,
            'fecha' => $fecha
        ];
    }

    public function getDatosGeneralesSN($id)
    {
        $task = new PersonController;
        $formatter = new NumeroALetras();
        //Primero buscamos el candidato
        $candidato = Person::findOrFail($id);
        $edad = $task->calculaedad($candidato->birth_date);
        $nombreCandidato = "" . $candidato->first_name . " " . $candidato->middle_name . " " . $candidato->last_name . "";
        $candidatoEdad = $formatter->toString($edad);
        $candidatoProfesion = $candidato->professional_title;
        $candidatoCiudad = $candidato->city;
        $candidatoDepartamento = $candidato->department;
        $candidatoProfesion = $candidato->professional_title;
        //Identificamos el tipo de candidato si es Nacional o Nacionalizado
        if ($candidato->is_nationalized) {
            $candidato->resident_card_number;
            $documento = 'DOCUMENTO DE CARNET DE RESIDENCIA NUMERO ' . $formatter->toString($candidato->resident_card_number);
        } else {
            $documento = 'DOCUMENTO UNICO DE IDENTIDAD NUMERO ' . $candidato->dui_text;
        }
        $candiatoNit = $candidato->nit_text;
        return [
            'nombreCandidato' => $nombreCandidato,
            'edadCandidato' => $candidatoEdad,
            'profesionCandidato' => $candidatoProfesion,
            'ciudadCandidato' => $candidatoCiudad,
            'departamentoCandidato' => $candidatoDepartamento,
            'documentoCandidato' => $documento,
            'nitCandidato' => $candiatoNit,
            'candidatoProfesion' => $candidatoProfesion
        ];
    }

    public function getDatosGeneralesExtranjero($id)
    {
        $task = new PersonController;
        $formatter = new NumeroALetras();
        //Primero buscamos el candidato
        $candidato = Person::findOrFail($id);
        $edad = $task->calculaedad($candidato->birth_date);
        $nombreCandidato = "" . $candidato->first_name . " " . $candidato->middle_name . " " . $candidato->last_name . "";
        $candidatoEdad = $formatter->toString($edad);
        $candidatoProfesion = $candidato->professional_title;
        $nacionalidad = $candidato->nationality;
        $pasaporte = $candidato->passport_number;
        $profesion = $candidato->professional_title;
        return [
            'nombreCandidato' => $nombreCandidato,
            'edadCandidato'   => $candidatoEdad,
            'profesionCandidato' => $candidatoProfesion,
            'nacionalidad' => $nacionalidad,
            'pasaporte' => $pasaporte,
        ];
    }

    public function getPrincipalData($id)
    {
        $candidato = Person::findOrFail($id);
        //Primero obtenermos los datos comunes de los contrato
        $comunes = $this->getPrincipalinfo();
        if ($candidato->nationality == 'El Salvador') {
            $person = $this->getDatosGeneralesSN($candidato->id);
            $tipo = 'N';
        } else {
            $person = $this->getDatosGeneralesExtranjero($candidato->id);
            $tipo = 'E';
        }
        return [
            'comunes' => (object)$comunes,
            'person' => (object)$person,
            'tipo' => $tipo
        ];
    }

    public function getSchoolNameFromRequest($requestDetails)
    {
        if ($requestDetails->hiringRequest->school->id == 9) {
            $escuela = $requestDetails->hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $requestDetails->hiringRequest->school->name;
        }
        return $escuela;
    }

    public function getRequestActivities($requestDetails)
    {
        $cargo = [];
        $allActivities = [];
        foreach ($requestDetails->positionActivity  as $positionActivity) {
            $activities = [];
            $cargo[] = $positionActivity->position->name;
            $activitiesString = mb_strtoupper($positionActivity->position->name, 'UTF-8') . ': ';
            foreach ($positionActivity->activities as $activity) {
                $activities[] = $activity->name;
            }
            $allActivities[] = $activitiesString . implode(', ', $activities);
        }
        return  ['cargo' => implode(', ', $cargo), 'cargoFunciones' => implode('; ', $allActivities)];
    }

    public function getContractPeriodString($formatter, $requestDetails)
    {
        $fechaInicio = Carbon::parse($requestDetails->start_date)->locale('es');
        $fechaFinal = Carbon::parse($requestDetails->end_date)->locale('es');
        return "DEL " . $formatter->toString($fechaInicio->day) . " DE " . $fechaInicio->monthName . " DE " . $formatter->toString($fechaInicio->year) . " AL " . $formatter->toString($fechaFinal->day) . " DE " . $fechaFinal->monthName . " DE " . $formatter->toString($fechaFinal->year);
    }

    public function getHiringGroupsScheduleString($requestDetails)
    {
        $horarios = "";
        foreach ($requestDetails->hiringGroups as $hg) {
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
            $horarios = $horarios . "" . "" . "(" . $hg->group->course->name . " " . $hg->group->grupo->name . "-" . $hg->group->number . ") " . $days . " - " . $times . " ";
        }
        return $horarios;
    }

    public function fillWordFile($phpWord, $dataArray)
    {
        foreach ($dataArray as $valueKey => $value) {
            $phpWord->setValue($valueKey, mb_strtoupper($value, 'UTF-8'));
        }
    }

    public function agreementContract($requestDetails)
    {
        $formatter = new NumeroALetras();
        $fechaJD = Carbon::parse($requestDetails->hiringRequest->agreement->agreed_on)->locale('es');
        $fechaAcuerdo = $formatter->toString($fechaJD->day) . " DE " . $fechaJD->monthName . " DE " . $formatter->toString($fechaJD->year) . "";
        $codigoAcuerdo = $requestDetails->hiringRequest->agreement->code;
        if (preg_match('/(\w+)-(\d+)\/(\d+)/', $codigoAcuerdo, $matches)) {
            $formatter = new NumeroALetras();
            $parte1 = $matches[1];
            $parte2 = $formatter->toString($matches[2]);
            $parte3 = $formatter->toString($matches[3]);
            $codigoAcuerdo = $parte1 . '-' . $parte2 . '/ ' . $parte3;
        }
        return [
            'fechaAcuerdo' => $fechaAcuerdo,
            'codigoAcuerdo' => $codigoAcuerdo,
        ];
    }


    public function contractGenerateServiciosProfesionales($requestDetails)
    {
        $formatN =  Format::where('type', 'Contrato por Servicios Profesionales no Personales')->where('type_contract', 'Nacional')->where('is_active', 1)->first();
        $formatI =  Format::where('type', 'Contrato por Servicios Profesionales no Personales')->where('type_contract', 'Internacional')->where('is_active', 1)->first();
        $docTemplatePath = ['N' => $formatN->file_url, 'E' => $formatI->file_url];
        $formatter = new NumeroALetras();
        $personalData = $this->getPrincipalData($requestDetails->person_id);
        $escuela = $this->getSchoolNameFromRequest($requestDetails);
        $acuerdo = $this->agreementContract($requestDetails);
        $actividades = $this->getRequestActivities($requestDetails);
        $peridoContrato = $this->getContractPeriodString($formatter, $requestDetails);

        //Total de horas
        $subtotal = 0;
        $subtiempo = 0;
        $Horas = 0;
        foreach ($requestDetails->hiringGroups as $group) {
            $subtotal += $group->hourly_rate * $group->work_weeks * $group->weekly_hours;
            $subtiempo += $group->weekly_hours * $group->work_weeks;
            $hourly_rate = $group->hourly_rate;
        }

        $Horas += $subtiempo;
        $horasTotales = $Horas . " HORAS";
        $valorHora = "$" . sprintf('%.2f', $hourly_rate);
        $valorTotal = explode('.', sprintf('%.2f', $subtotal));
        $sueldoLetras = $formatter->toString($subtotal) . "" . $valorTotal[1] . "/100 DOLARES DE LOS ESTADOS UNIDOS DE AMERICA ($" . $subtotal . ")";

        if ($personalData['tipo'] == 'E') {
            $sueldoLetras = $sueldoLetras . " MENOS EL 20% DE RENTA, según deducciones establecidas por las leyes de El salvador";
        }
        $horarios = $this->getHiringGroupsScheduleString($requestDetails);
        try {
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path($docTemplatePath[$personalData['tipo']]));
            $specific = [
                'escuelaContratante' => mb_strtoupper($escuela, 'UTF-8'),
                'cargoCandidato' => $actividades['cargo'],
                'funcionesCandidato' => $actividades['cargoFunciones'],
                'periodoDelContrato' => mb_strtoupper($peridoContrato, 'UTF-8'),
                'horasTotales' => mb_strtoupper($horasTotales, 'UTF-8'),
                'valorHora' => mb_strtoupper($valorHora, 'UTF-8'),
                'sueldoLetras' => mb_strtoupper($sueldoLetras, 'UTF-8'),
                'horarioCandidato' => mb_strtoupper($horarios, 'UTF-8')
            ];
            $this->fillWordFile($phpWord, $acuerdo);
            $this->fillWordFile($phpWord, $personalData['comunes']);
            $this->fillWordFile($phpWord, $personalData['person']);
            $this->fillWordFile($phpWord, $specific);

            $header = [
                "Content-Type: application/octet-stream",
            ];
            return ['document' => $phpWord, 'header' => $header, 'name' => 'Contrato Generado Servicios Profesionales.docx'];
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            return back($e->getCode());
        }
    }

    public function contractGenerateTiempoIntegral($requestDetails)
    {
        $docTemplatePath = ['N' => '/TI-N.docx', 'E' => '/TI-I.docx'];

        //Obtenermos los datos generales del contrato y la informacion personal del candidato
        $personalData = $this->getPrincipalData($requestDetails->person_id);
        $acuerdo = $this->agreementContract($requestDetails);
        //Obtenemos la partida,cargo,salario y total a pagar 
        $formatter = new NumeroALetras();
        $partida = $formatter->toString($requestDetails->person->employee->partida);
        $escalafon = $requestDetails->person->employee->escalafon->name;
        $sal = $requestDetails->monthly_salary;
        $valorTotalSalario = explode('.', sprintf('%.2f', $sal));
        $salario = $formatter->toString($sal) . "" . $valorTotalSalario[1] . "/100 DOLARES DE LOS ESTADOS UNIDOS DE AMERICA ($" . number_format($sal, 2) . ")";
        $porcentaje = $formatter->toString($requestDetails->salary_percentage * 100) . "POR CIENTO (" . ($requestDetails->salary_percentage * 100) . "%);";
        $totalAPagar = $requestDetails->work_months * $requestDetails->monthly_salary * $requestDetails->salary_percentage;
        $valorTotal = explode('.', sprintf('%.2f', $totalAPagar));
        $sueldoLetras = $formatter->toString($totalAPagar) . "" . $valorTotal[1] . "/100 DOLARES DE LOS DE LOS ESTADOS UNIDOS DE AMERICA ($" . number_format($totalAPagar, 2) . ")";

        if ($personalData['tipo'] == 'E') {
            $sueldoLetras = $sueldoLetras . " MENOS EL 20% DE RENTA, según deducciones establecidas por las leyes de El salvador";
        }
        //Funciones en tiempo Normal
        $staySchedule = StaySchedule::where(['id' => $requestDetails->stay_schedule_id])->with(['semester', 'scheduleDetails', 'scheduleActivities'])->firstOrFail();
        $hrStay = "";
        $hrStayNumber = 0;
        foreach ($staySchedule->scheduleDetails as $schedule) {
            $hrStayNumber += (strtotime($schedule->finish_time) -  strtotime($schedule->start_time)) / 3600;
            $hrStay = $hrStay . " " . $schedule->day . " - " . date("g:ia", strtotime($schedule->start_time)) . ' a ' . date("g:ia", strtotime($schedule->finish_time)) . ",";
        }
        $hrAct = "";
        foreach ($staySchedule->scheduleActivities as $act) {
            $hrAct = $hrAct . " " . $act->name . ",";
        }
        //funciones y horarios en tiemo integral
        $actividades = $this->getRequestActivities($requestDetails);

        $horasSemanalesIntegral = 0;
        $horariosIntegral = "";
        foreach ($requestDetails->groups as $group) {
            $days = [];
            $times = [];
            foreach ($group->schedule as $schedule) {
                $horasSemanalesIntegral += (strtotime($schedule->finish_hour) -  strtotime($schedule->start_hour)) / 3600;
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
            $horariosIntegral = $horariosIntegral . "" . "" . "(" . $group->course->name . " " . $group->grupo->name . "-" . $group->number . ") " . $days . " - " . $times . " ";
        }

        $formatter = new NumeroALetras();
        $peridoContracion = $this->getContractPeriodString($formatter, $requestDetails);

        try {
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path($docTemplatePath[$personalData['tipo']]));

            $specific = [
                'partida' => mb_strtoupper($partida, 'UTF-8'),
                'cargo' => mb_strtoupper($escalafon, 'UTF-8'),
                'justificacion' => mb_strtoupper($requestDetails->justification, 'UTF-8'),
                'metas' => mb_strtoupper($requestDetails->goals, 'UTF-8'),
                'salario' =>  $salario,
                'funcionesPermanencia' => mb_strtoupper($hrAct, 'UTF-8'),
                'horarioPermanencia' => mb_strtoupper($hrStay, 'UTF-8'),
                'horasSemanales' => $hrStayNumber,
                'cargoIntegral' => $actividades['cargo'],
                'funcionesIntegral' => $actividades['cargoFunciones'],
                'horarioIntegral' => mb_strtoupper($horariosIntegral, 'UTF-8'),
                'horasIntegral' => sprintf('%.2f', $horasSemanalesIntegral),
                'periodoDeContratacion' => mb_strtoupper($peridoContracion, 'UTF-8'),
                'salarioIntegral' => mb_strtoupper($sueldoLetras, 'UTF-8'),
                'porcentaje' => $porcentaje,
            ];
            $this->fillWordFile($phpWord, $acuerdo);
            $this->fillWordFile($phpWord, $personalData['comunes']);
            $this->fillWordFile($phpWord, $personalData['person']);
            $this->fillWordFile($phpWord, $specific);

            $header = [
                "Content-Type: application/octet-stream",
            ];
            return ['document' => $phpWord, 'header' => $header, 'name' => 'Contrato Generado Tiempo Integral.docx'];
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            return back($e->getCode());
        }
    }


    public function contractGenerateTiempoAdicional($requestDetails)
    {
        $docTemplatePath = ['N' => '/TA-N.docx', 'E' => '/TA-I.docx'];
        //Obtenermos los datos generales del contrato y la informacion personal del candidato
        $personalData = $this->getPrincipalData($requestDetails->person_id);
        $acuerdo = $this->agreementContract($requestDetails);
        $formatter = new NumeroALetras();
        $escalafon = $requestDetails->person->employee->escalafon->name;
        $sal = $requestDetails->person->employee->escalafon->salary;
        $valorTotalSalario = explode('.', sprintf('%.2f', $sal));
        $salario = $formatter->toString($sal) . "" . $valorTotalSalario[1] . "/100 DOLARES DE LOS ESTADOS UNIDOS DE AMERICA ($" . number_format($sal, 2) . ")";
        $totalAPagar = $requestDetails->hourly_rate * $requestDetails->work_weeks * $requestDetails->weekly_hours;
        $valorTotal = explode('.', sprintf('%.2f', $totalAPagar));
        $sueldoLetras = $formatter->toString($totalAPagar) . "" . $valorTotal[1] . "/100 DOLARES DE LOS DE LOS ESTADOS UNIDOS DE AMERICA ($" . sprintf('%.2f', $totalAPagar) . ")";
        if ($personalData['tipo'] == 'E') {
            $sueldoLetras = $sueldoLetras . " MENOS EL 20% DE RENTA, según deducciones establecidas por las leyes de El salvador";
        }
        //Funciones en tiempo Normal
        $staySchedule = StaySchedule::where(['id' => $requestDetails->stay_schedule_id])->with(['semester', 'scheduleDetails', 'scheduleActivities'])->firstOrFail();
        $hrStay = "";
        $hrStayNumber = 0;
        foreach ($staySchedule->scheduleDetails as $schedule) {
            $hrStayNumber += (strtotime($schedule->finish_time) -  strtotime($schedule->start_time)) / 3600;
            $hrStay = $hrStay . " " . $schedule->day . " - " . date("g:ia", strtotime($schedule->start_time)) . ' a ' . date("g:ia", strtotime($schedule->finish_time)) . ",";
        }
        $hrAct = "";
        foreach ($staySchedule->scheduleActivities as $act) {
            $hrAct = $hrAct . " " . $act->name . ",";
        }
        $valorTotalHora = explode('.', sprintf('%.2f', $requestDetails->hourly_rate));
        $valorHora = $formatter->toString($valorTotalHora[0]) . "" . $valorTotalHora[1] . "/100 DOLARES DE LOS DE LOS ESTADOS UNIDOS DE AMERICA ($" . sprintf('%.2f', $requestDetails->hourly_rate) . ")";
        //funciones y horarios en tiemo integral
        $actividades = $this->getRequestActivities($requestDetails);
        $formatter = new NumeroALetras();
        $peridoContracion = $this->getContractPeriodString($formatter, $requestDetails);
        $docTemplatePath[$personalData['tipo']];
        try {
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path($docTemplatePath[$personalData['tipo']]));

            $specific = [

                'cargo' => mb_strtoupper($escalafon, 'UTF-8'),
                'salario' => $salario,
                'funcionesPermanencia' => mb_strtoupper($hrAct, 'UTF-8'),
                'horarioPermanencia' => mb_strtoupper($hrStay, 'UTF-8'),
                'horasSemanales' => $hrStayNumber,
                'cargoAdicional' => $actividades['cargo'],
                'funcionesAdicional' => $actividades['cargoFunciones'],
                'horarioAdicional' => mb_strtoupper($hrStay, 'UTF-8'),
                'horasAdicional' => sprintf('%.2f', $requestDetails->weekly_hours),
                'horasAdicionalPeriodo' => sprintf('%.2f', $requestDetails->weekly_hours * $requestDetails->work_weeks),
                'periodoDeContratacion' => mb_strtoupper($peridoContracion, 'UTF-8'),
                'salarioAdicional' => mb_strtoupper($sueldoLetras, 'UTF-8'),
                'valorHora' => mb_strtoupper($valorHora, 'UTF-8'),
            ];
            $this->fillWordFile($phpWord, $acuerdo);
            $this->fillWordFile($phpWord, $personalData['comunes']);
            $this->fillWordFile($phpWord, $personalData['person']);
            $this->fillWordFile($phpWord, $specific);

            $header = [
                "Content-Type: application/octet-stream",
            ];

            return ['document' => $phpWord, 'header' => $header, 'name' => 'Contrato Generado Tiempo Adicional.docx'];
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            return back($e->getCode());
        }
    }

    private function generateContractFileName($requestDetail)
    {
        $version = $requestDetail->contract_version ? $requestDetail->contract_version + 1 : 1;
        $fullName = $requestDetail->person->first_name . $requestDetail->person->middle_name . $requestDetail->person->last_name;
        $formattedName = str_replace(' ', '', $fullName);
        $reqCode = $requestDetail->hiringRequest->code;
        return [
            'name' => $reqCode . '-' . $formattedName . '-v' . $version . '.docx',
            'version' => $version,
        ];
    }

    public function generateContract($requestDetailId)
    {
        $requestDetails = HiringRequestDetail::with([
            'person',
            'groups',
            'hiringGroups',
            'hiringRequest',
            'hiringRequest.agreement',
            'positionActivity.activities',
            'positionActivity.position',
            'hiringRequest.school',
        ])->findOrFail($requestDetailId);

        if ($requestDetails->hiringRequest->agreement->approved != true) {
            return response(['message' => 'El contrato no se puede generar porque la solicitud no ha sido aprobada en Junta Directiva'], 400);
        }

        if ($requestDetails->contract_file != null) {
            $this->RegisterAction('El usuario descargo el contrato con id: ' . $requestDetails->id, 'high');
            return \Storage::disk('contracts')->download($requestDetails->contract_file);
        }

        switch ($requestDetails->hiringRequest->contractType->name) {
            case ContractType::TA:
                $contractComponents = $this->contractGenerateTiempoAdicional($requestDetails);
                break;

            case ContractType::TI:
                $contractComponents = $this->contractGenerateTiempoIntegral($requestDetails);
                break;

            case ContractType::SPNP:
                $contractComponents = $this->contractGenerateServiciosProfesionales($requestDetails);
                break;

            default:
                return response(['message' => 'No se encontró el tipo de contrato'], 404);
                break;
        }

        $fileName = $this->generateContractFileName($requestDetails);
        $file = $contractComponents['document'];
        $header = $contractComponents['header'];

        $tempFile = tempnam(sys_get_temp_dir(), 'ContractFile');
        $file->saveAs($tempFile);

        Storage::disk('contracts')->put($fileName['name'], \File::get($file));
        $requestDetails->update([
            'contract_file' => $fileName['name'],
            'contract_version' => $fileName['version'],
        ]);

        $this->RegisterAction('El usuario genero la version inicial del contrato con id: ' . $requestDetails->id, 'high');
        return response()->download($tempFile, $fileName, $header)->deleteFileAfterSend(true);
    }


    public function updateContract($id, Request $request)
    {
        $request->validate(['file' => 'required|mimes:doc,docx']);
        $requestDetail = HiringRequestDetail::findOrFail($id);

        $file = $request->file('file');
        $fileName = $this->generateContractFileName($requestDetail);

        Storage::disk('contracts')->put($fileName['name'], \File::get($file));
        $requestDetail->update([
            'contract_file' => $fileName['name'],
            'contract_version' => $fileName['version'],
        ]);

        $this->RegisterAction('El usuario actualizado el contrato con id: ' . $requestDetail->id, 'critical');
        return response(['message' => 'El contrato se ha actualizado exitosamente'], 200);
    }
}
