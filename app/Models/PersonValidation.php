<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dui_readable',
        'name_correct',
        'address_correct',
        'dui_current',
        'nit_readable',
        'curriculum_readable',
        'profesional_title_readable',
        'profesional_title_validated',
        'bank_account_readable',
        'person_id',
    ];
}
