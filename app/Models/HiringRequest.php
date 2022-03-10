<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'code',
        'request_status',
        'validated',
        'comments',
        'modality',
        'message',
        'contract_type_id',
        'school_id',
        'fileName',
    ];

    protected $appends = ['last_status',];
    public function getLastStatusAttribute()
    {
        return $this->status->last()->makeHidden(['pivot']);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function status()
    {
        return $this->belongsToMany(Status::class, 'status_history', 'hiring_request_id', 'status_id')->withTimeStamps();
    }

    public function details()
    {
        return $this->hasMany(HiringRequestDetail::class);
    }

    public function agreement()
    {
        return $this->hasOne(Agreement::class);
    }
}
