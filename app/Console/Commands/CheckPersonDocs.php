<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;
use App\Models\Person;
use App\Mail\ValidationDocsNotification;

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
            if ($candidate->doc_expiration_date < date('Y-m-d')) {
            }
            \Log::info($candidates);
         }
    }
}
