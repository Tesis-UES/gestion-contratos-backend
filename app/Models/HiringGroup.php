<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiringGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'hourly_rate',
        'work_weeks',
        'weekly_hours',
        'period_hours',
        'group_id',
        'hiring_request_detail_id',
    ];

    public function hiringRequestDetail()
    {
        return $this->belongsTo(HiringRequestDetail::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
