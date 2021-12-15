<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'year',
        'next_code',
    ];
}
