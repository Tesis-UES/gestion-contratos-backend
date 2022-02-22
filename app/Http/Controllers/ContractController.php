<?php

namespace App\Http\Controllers;

use App\Models\Bank;
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
            'duiTextoRector' => $duiTextoRector,
            'nitTextoRector' => $nitTextoRector,
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
            'candidatoEdad' => $candidatoEdad,
            'candidatoProfesion' => $candidatoProfesion,
            'candidatoCiudad' => $candidatoCiudad,
            'candidatoDepartamento' => $candidatoDepartamento,
            'documentoDC' => $documento,
            'candidatoNit' => $candiatoNit,
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
            'candidatoEdad'   => $candidatoEdad,
            'candidatoProfesion' => $candidatoProfesion,
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

    public function contractGenerateServiciosProfesionales()
    {

        $requestDetails = HiringRequestDetail::with(['HiringGroups', 'activities', 'hiringRequest.school'])->findOrFail(3);
        //Obtenermos los datos generales del contrato y la informacion personal del candidato
        $personalData = (object) $this->getPrincipalData($requestDetails->person_id);
        
        //Se prepara la informacion pertienente a la parte del contrato de servicios profesionales
        // return $requestDetails->activities;
        //Nombre de la escuela o unidad contratante
        if ($requestDetails->hiringRequest->school->id == 9) {
            $escuela = $requestDetails->hiringRequest->school->name;
        } else {
            $escuela = "Escuela de " . $requestDetails->hiringRequest->school->name;
        }

        $actividades = "";
        foreach ($requestDetails->activities as $activity) {
            $actividades = $actividades . "" . $activity->name . ", ";
        }
        $formatter = new NumeroALetras();
        $fechaInicio = Carbon::parse($requestDetails->start_date)->locale('es');
        $fechaFinal = Carbon::parse($requestDetails->end_date)->locale('es');
        
        $peridoContrato = "DEL " . $formatter->toString($fechaInicio->day) . " DE " . $fechaInicio->monthName . " DE " . $formatter->toString($fechaInicio->year) . " AL ". $formatter->toString($fechaFinal->day) . " DE " . $fechaFinal->monthName . " DE " . $formatter->toString($fechaFinal->year)  ;
        
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
           $horasTotales = $Horas. " HORAS";
           $valorHora = "$".sprintf('%.2f',$hourly_rate);
           $valorTotal = explode( '.', sprintf('%.2f',$subtotal));
           if($personalData->tipo == 'N'){
              $sueldoLetras = $formatter->toString($subtotal)."".$valorTotal[1]."/100 DOLARES DE LOS DE LOS ESTADOS UNIDOS DE AMERICA ($" .$subtotal. ")";
           }else{
             $sueldoLetras = $formatter->toString($subtotal)."".$valorTotal[1]."/100 DOLARES DE LOS DE LOS ESTADOS UNIDOS DE AMERICA ($" .$subtotal. ") MENOS EL 20% DE RENTA, segun deducciones establecidas por las leyes de El salvador";
           }
           
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
            $horarios = $horarios."".""."(".$hg->group->course->name." ".$hg->group->grupo->name . "-" . $hg->group->number.") ".$days." - ".$times." ";
        }
        try {
            if($personalData->tipo == 'N'){
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path('/SPNP-N.docx'));
                $phpWord->setValue('candidatoCiudad', strtoupper($personalData->person->candidatoCiudad));
                $phpWord->setValue('candidatoDepartamento', strtoupper($personalData->person->candidatoDepartamento));
                $phpWord->setValue('documento', strtoupper($personalData->person->documentoDC));
                $phpWord->setValue('candidatoNit', strtoupper($personalData->person->candidatoNit));
            }else{
                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path('/SPNP-I.docx'));
                $phpWord->setValue('nacionalidad', strtoupper($personalData->person->nacionalidad));
                $phpWord->setValue('pasaporte', strtoupper($personalData->person->pasaporte));
            }
           

            $phpWord->setValue('numeroAcuerdo','FIA-SPNP-N-001');
            $phpWord->setValue('nombreRector', strtoupper($personalData->comunes->nombreRector));
            $phpWord->setValue('firmaRector', $personalData->comunes->firmaRector);
            $phpWord->setValue('edadRector', strtoupper($personalData->comunes->edadRector));
            $phpWord->setValue('duiTextoRector', strtoupper($personalData->comunes->duiTextoRector));
            $phpWord->setValue('nitTextoRector', strtoupper($personalData->comunes->nitTextoRector));
            $phpWord->setValue('profesionRector', strtoupper($personalData->comunes->profesionRector));
            $phpWord->setValue('fecha', strtoupper($personalData->comunes->fecha));

            $phpWord->setValue('nombreCandidato', strtoupper($personalData->person->nombreCandidato));
            $phpWord->setValue('candidatoEdad', strtoupper($personalData->person->candidatoEdad));
            $phpWord->setValue('candidatoProfesion', strtoupper($personalData->person->candidatoProfesion));
            
           

            $phpWord->setValue('escuelaContratante', mb_strtoupper($escuela, 'UTF-8'));
            $phpWord->setValue('cargoCandidato', mb_strtoupper($requestDetails->position, 'UTF-8'));
            $phpWord->setValue('actividadesCandidato', mb_strtoupper($actividades, 'UTF-8'));
            $phpWord->setValue('periodoDelContrato', mb_strtoupper($peridoContrato, 'UTF-8'));
            $phpWord->setValue('horasTotales', mb_strtoupper($horasTotales, 'UTF-8'));
            $phpWord->setValue('valorHora', mb_strtoupper($valorHora, 'UTF-8'));
            $phpWord->setValue('sueldoLetras', mb_strtoupper($sueldoLetras, 'UTF-8'));
            $phpWord->setValue('horarioCandidato', mb_strtoupper($horarios, 'UTF-8'));

            $tenpFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $phpWord->saveAs($tenpFile);

            $header = [
                "Content-Type: application/octet-stream",
            ];
            return response()->download($tenpFile, 'Contrato Generado Servicios Profesionales.docx', $header)->deleteFileAfterSend($shouldDelete = true);
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            //throw $th;
            return back($e->getCode());
        }
    }
}
