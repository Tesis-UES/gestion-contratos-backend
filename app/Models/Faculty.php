<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

     //Campos pertenecientes al modelo
     protected $fillable = [
        'name',
        'dean',
        'viceDean',
];

}
