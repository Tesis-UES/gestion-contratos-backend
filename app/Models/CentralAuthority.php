<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentralAuthority extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'position',
        'firstName',
        'middleName',
        'lastName',
        'dui',
        'nit',
        'start_period',
        'end_period',
        'text_dui',
        'text_nit',
        'birth_date',
        'profession',
        'reading_signature'
    ];
}
