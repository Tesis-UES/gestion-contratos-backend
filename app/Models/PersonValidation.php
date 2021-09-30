<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonValidation extends Model
{
    use HasFactory;

    protected $fillable = [
       'dui_readable',
           'dui_name',
           'dui_number',
           'dui_profession',
           'dui_civil_status',
           'dui_birth_date',
           'dui_unexpired',
           'dui_address',
            //Validaciones Correspondientes al NIT
           'nit_readable',
           'nit_name', 
           'nit_number',
            //Validaciones Correspondientes a la cuenta de Banco
           'bank_readable',
           'bank_number', 
            //Validaciones Correspondientes al curriculum
           'curriculum_readable',
            //Validaciones Correspondientes al Permiso de trabajo de otra facultad
           'work_permission_readable',
            //Validaciones Correspondientes al Pasaporte
           'passport_readable',
           'passport_name',
           'passport_number',
            //Validaciones Correspondientes al titulo
           'title_readable',
            //Nacional
           'title_mined',
            //Internacional
           'title_apostilled',
           'title_apostilled_readable',
           'title_authentic',
    ];
}
