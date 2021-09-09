<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonChange extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'person_id',
        'change'
    ];
}
