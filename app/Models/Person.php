<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;
    //Campos pertenecientes al modelo
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'know_as',
        'civil_status',
        'married_name',
        'birth_date',
        'gender',
        'telephone',
        'email',
        'nationality',
        'is_employee',
        'employee_type',
        'journey_type',
        'request_to_same_faculty',
        'address',
        'dui',
        'dui_number',
        'dui_expiration_date',
        'nit',
        'nit_number',
        'curriculum',
        'professional_title',
        'professional_title_scan',
        'bank_account',
        'bank_account_number',
        'work_permission',
    ];
}
