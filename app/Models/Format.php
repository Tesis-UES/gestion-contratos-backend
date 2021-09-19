<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Format extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'fileUrl',
    ];

    protected $hidden = [
        'fileUrl',
    ];
}
