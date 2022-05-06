<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escalafon extends Model
{
    use HasFactory, SoftDeletes;

    //Campos pertenecientes al modelo
    protected $fillable = [
            'code',
            'name',
           'salary',
           'hour_price',
    ];
}
