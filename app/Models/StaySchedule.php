<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaySchedule extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'semester_id',
        'employee_id',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function scheduleDetails() 
    {
        return $this->hasMany(StayScheduleDetail::class);
    }

    public function scheduleActivities() 
    {
        return $this->hasMany(StayScheduleActivity::class);
    }
}
