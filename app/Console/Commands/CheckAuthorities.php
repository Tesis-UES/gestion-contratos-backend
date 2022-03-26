<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ValidationDocsNotification;
use Carbon\Carbon;
use Mail;
use App\Models\CentralAuthority;
use App\Models\FacultyAuthority;
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
        
        foreach ($facultyAuthorities as $fca) {
            $endPeriod = Carbon::parse($fca->endPeriod);
            $actualDate = Carbon::now();
            $days = $endPeriod->diffInDays($actualDate);
            
        }
        



    }
}
