<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayScheduleDetail extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'day',
        'start_time',
        'finish_time',
        'stay_schedule_id',
    ];

    public function staySchedule() 
    {
        return $this->belongsTo(StaySchedule::class);
    }
}
