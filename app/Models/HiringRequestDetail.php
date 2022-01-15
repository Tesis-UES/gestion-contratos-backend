<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiringRequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'finish_date',
        'schedule_file',
        'position',
        'goal',
        'work_months',
        'salariy_percentage',
        'hourly_rate',
        'work_weeks',
        'weekly_hours',
        'person_id',
        'stay_schedule_id',
        'hiring_request_id',
    ];

    protected $hidden = ['pivot'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function staySchedule()
    {
        return $this->belongsTo(StaySchedule::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'hiring_groups');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'hiring_activities');
    }

    public function hiringRequest()
    {
        return $this->belongsTo(HiringRequest::class);
    }
}
