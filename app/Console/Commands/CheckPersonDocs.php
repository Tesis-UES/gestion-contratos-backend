<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;
use App\Models\Person;
use App\Mail\ValidationDocsNotification;
use Carbon\Carbon;
use Mail;

class CheckPersonDocs extends Command
{
    protected $signature = 'command:checkPersonDocs';

    protected $description = 'Comando que revisa si los documentos de las personas estan vigentes segun la fecha de vencimiento';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $candidates = Person::all();
        foreach ($candidates as $candidate) {
            if (!$candidate->dui_expiration_date == null) {
                $expirationDate = Carbon::parse($candidate->dui_expiration_date);
                $actualDate = Carbon::now();
                $days = $expirationDate->diffInDays($actualDate);
                
                if($expirationDate < $actualDate){
                    if ($days == 30 || $days == 15 || $days == 5 || $days == 0) {
                        $mensajeEmail = "Se le notifica que su DUI está a ".$days." días  por vencer, por favor proceda a actualizar el escaneo de su DUI y actualizar su nueva fecha de vencimiento.";
                     
                            try {
                                Mail::to($candidate->user->email)->send(new ValidationDocsNotification($mensajeEmail, 'notificacionExpDoc'));
                            } catch (\Swift_TransportException $e) {
                            }
                        
                    }
                }
               
            }

            if (!$candidate->resident_expiration_date == null) {
                $expirationDate = Carbon::parse($candidate->resident_expiration_date);
                $actualDate = Carbon::now();
                $days = $expirationDate->diffInDays($actualDate);
                
                if($expirationDate < $actualDate){
                    if ($days == 30 || $days == 15 || $days == 5 || $days == 0) {
                        $mensajeEmail = "Se le notifica que su CARNET DE RESIDENCIA está a ".$days." días  por vencer, por favor proceda a actualizar el escaneo de su CARNET DE RESIDENCIA y actualizar su nueva fecha de vencimiento.";
                     
                            try {
                                Mail::to($candidate->user->email)->send(new ValidationDocsNotification($mensajeEmail, 'notificacionExpDoc'));
                            } catch (\Swift_TransportException $e) {
                            }
                        
                    }
                }
               
            }
        }
    }
}
