<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;

class CheckPersonDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkPersonDocs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que revisa si los documentos de las personas estan vigentes segun la fecha de vencimiento';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $texto = "PRUEBA PRELIMINAR 1";
        Storage::append('logs.txt', $texto);	
    }
}
