<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Semester;
use App\Mail\ValidationDocsNotification;
use Carbon\Carbon;
use Mail;
use App\Http\Controllers\EscalafonController;


class CheckCiclo extends Command
{
    
    protected $signature = 'command:SemesterCheck';

  
    protected $description = 'Comando que revisa la fecha de vencimiento del ciclo activo en el sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        $task = new EscalafonController();
        $adminEmail = $task->getAdminMail();
        $cis = Semester::where('status',true)->get();
        foreach ($cis as $ci ) {
          
        $fecha_vencimiento = Carbon::parse($ci->end_date);
        $fecha_actual = Carbon::now();
        $dias = $fecha_vencimiento->diffInDays($fecha_actual); 
         if ($dias == 15 || $dias == 5 || $dias == 0) {
            if($dias == 0){
                $mensajeEmail = "El ciclo actual ha vencido ". $ci->name ."Por lo cual se ha dado por finalizado y puesto como ciclo inactivo, ingrese al sistema para registrar un nuevo ciclo";
                $ci->status = false;
                $ci->save();
            }else{
                $mensajeEmail = "Se le notifica que el ciclo actual registrado en el sistema está a ".$dias." días por vencer, El sistema inactivara automaticamente el ciclo cuando se cumpla la fecha de fin de periodo de ciclo.";

            }
            foreach ($adminEmail as $email) {
                try {
                    Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail, 'notificacionCiclos'));
                } catch (\Swift_TransportException $e) {
                }
            }
        } 
        }

    }
}
