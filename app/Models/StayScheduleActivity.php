<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayScheduleActivity extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'activity_id',
        'stay_schedule_id',
    ];

    protected $touches = ['staySchedule'];


    public function staySchedule() 
    {
        return $this->belongsTo(StaySchedule::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
