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
        'modality',
        'message',
        'contract_type_id',
        'school_id',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function status(){
        return $this->belongsToMany(Status::class,'status_history','hiring_request_id','status_id')->withTimeStamps();
    }
}
