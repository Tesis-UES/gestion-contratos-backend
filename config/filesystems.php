<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        /* Driver que contiene la ubicacion de los archivos personales de los candidatos*/
        'personalFiles' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/personalFiles',
        ],

        /* Driver que contiene la ubicacion de las solitudes de contratacion*/
        'hiringRequest' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/hiringRequest',
        ],

        /* Driver que contiene la ubicacion de los archivos personales unidos*/
        'personDocsMerged' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/personDocsMerged',
        ],

        /* Driver que contiene la ubicacion de los formatos*/
        'formats' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/formats',
        ],

        /* Driver que contiene la ubicacion de los acuerdos*/
        'agreements' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/agreements',
        ],

        /* Driver que contiene la ubicacion de los contratos*/
        'contracts' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/contracts',
        ],

        /* Driver que contiene la ubicacion de los horarios de detalle de solicitud de contratacion*/
        'requestDetailSchedules' => [
            'driver'    =>  'local',
            'root'      =>  public_path() . '/requestDetailSchedules',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
