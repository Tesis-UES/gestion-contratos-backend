<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringRequest extends Model
{
    use HasFactory,softdeletes;
    protected $fillable = [
        'id',
        'hiring_request_code',
        'contract_type_id',
        'school_id',
        'type_modality',
        'message',
        'status',
        'request_create',
        'request_send',

    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }
}
