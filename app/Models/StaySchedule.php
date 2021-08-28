<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaySchedule extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'semester_id',
        'professor_id',
    ];
}
