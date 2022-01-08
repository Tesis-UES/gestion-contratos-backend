<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'recommended',
    ];

    protected $hidden = ['pivot'];

    public function positions() 
    {
        return $this->belongsToMany(Position::class, 'position_activities');
    }

    public function staySchedules() 
    {
        return $this->belongsToMany(StaySchedule::class, 'stay_schedule_activities');
    }

    public function hiring_requests() {
        return $this->belongsToMany(Activity::class, 'hiring_activities');
    }
}
