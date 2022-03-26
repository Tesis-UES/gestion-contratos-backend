<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ValidationDocsNotification;
use Carbon\Carbon;
use Mail;
use App\Models\CentralAuthority;
use App\Models\FacultyAuthority;
use App\Models\SchoolAuthority;
use App\Http\Controllers\EscalafonController;

class CheckAuthorities extends Command
{
    protected $signature = 'command:checkAuthorities';

    protected $description = 'Comando que revisa el periodo de las Autoridades de las facultades y el Rector de la universidad, datos que son requeridos para la generación de solicitudes de contratos y Contratos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $task = new EscalafonController();
        $adminEmail = $task->getAdminMail();
        $facultyAuthorities = FacultyAuthority::all();
        $centralAuthorities = CentralAuthority::all();
        $schoolAuthorities = SchoolAuthority::all();
        $countSA = 0;
        $mensajeSA = array();
        foreach ($schoolAuthorities  as $sca) {
            $endPeriod = Carbon::parse($sca->endPeriod);
            $actualDate = Carbon::now();
            $days = $endPeriod->diffInDays($actualDate);
            if ($endPeriod < $actualDate) {
                if ($days == 30 || $days == 0) {
                    $countSA++;
                    $mensajeSa[] =  "<li>" . $sca->school->name . "<b> Fin del Periodo: " . $endPeriod->format('d/m/Y') . "</b></li>";
                }
            }
        }
        if ($countSA > 0) {
            $mensaje = "Por favor verificar las autoridades (Directores/Jefe de Unidad) de las escuelas/unidades a continuación listadas en el catalogo de autoridades de Escuelas de Sistema:<br>
            <b>Escuelas/Unidades:</b>
            <ul>" . implode(' ', $mensajeSa) . "</ul><br>
            Su periodo está cerca de caducar y necesitan ser actualizados.";
            foreach ($adminEmail as $email) {
                try {
                    Mail::to($email)->send(new ValidationDocsNotification($mensaje, 'notificacionAuthorities'));
                } catch (\Swift_TransportException $e) {
                }
            }
        }

        $countFA = 0;
        $mensajeFA = array();
        foreach ($facultyAuthorities  as $fa) {
            $endPeriod = Carbon::parse($fa->endPeriod);
            $actualDate = Carbon::now();
            $days = $endPeriod->diffInDays($actualDate);
            if ($endPeriod < $actualDate) {
                if ($days == 30 || $days == 0) {
                    $countFA++;
                    $mensajeFA[] =  "<li>" . $fa->faculty->name . " - " . $fa->position . "</b></li>";
                }
            }
        }
        if ($countFA > 0) {
            $mensaje = "Por favor verificar las autoridades de las Facultades a continuación listadas en el catalogo de Autoridades de Facultad del sistema:<br>
            <ul>" . implode(' ', $mensajeFA) . "</ul><br>
            Su periodo está cerca de caducar y necesitan ser actualizados.";
            foreach ($adminEmail as $email) {
                try {
                    Mail::to($email)->send(new ValidationDocsNotification($mensaje, 'notificacionAuthorities'));
                } catch (\Swift_TransportException $e) {
                }
            }
        }

        $countCA = 0;
        $mensajeCA = array();
        foreach ($centralAuthorities  as $Ca) {
            $endPeriod = Carbon::parse($Ca->endPeriod);
            $actualDate = Carbon::now();
            $days = $endPeriod->diffInDays($actualDate);
            if ($endPeriod < $actualDate) {
                if ($days == 30 || $days == 0) {
                    $countCA++;
                    $mensajeCA[] =  "<li>La información del  " . $Ca->position . " de la Universidad De El Salvador</b></li>";
                }
            }
        }
        if ($countCA > 0) {
            $mensaje = "Por favor verificar las autoridades Centrales a continuación listadas en el catalogo de Autoridades Centrales sistema:<br>
            <ul>" . implode(' ', $mensajeCA) . "</ul><br>
            Su periodo está cerca de caducar y necesitan ser actualizados.";
            foreach ($adminEmail as $email) {
                try {
                    Mail::to($email)->send(new ValidationDocsNotification($mensaje, 'notificacionAuthorities'));
                } catch (\Swift_TransportException $e) {
                }
            }
        }
    }
}
