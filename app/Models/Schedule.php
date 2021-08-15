<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'day',
        'start_hour',
        'finish_hour',
        'group_id',
    ];
}
