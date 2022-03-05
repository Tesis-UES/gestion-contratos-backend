<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPositionActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'hiring_request_detail_id'
    ];

    public function hiringRequestDetail()
    {
        return $this->belongsTo(HiringRequestDetail::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'hiring_activities');
    }
}
