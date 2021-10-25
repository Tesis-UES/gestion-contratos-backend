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
        'status',
        'married_name',
        'birth_date',
        'gender',
        'telephone',
        'email',
        'nationality',
        'city',
        'department',
        'address',
        'dui',
        'nup',
        'isss_number',
        'passport_number',
        'passport',
        'dui_number',
        'dui_expiration_date',
        'nit',
        'nit_number',
        'curriculum',
        'professional_title',
        'professional_title_scan',
        'bank_id',
        'bank_account',
        'bank_account_number',
        'work_permission',
        'reading_signature',
        'dui_text',
        'nit_text',
        'alternate_telephone',
        'alternate_mail' ,
        'is_employee'
    ];

    public function personValidations()
    {
        return $this->hasOne(PersonValidation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function bank()
    {
        return $this->BelongsTo(Bank::class);
    }
}
