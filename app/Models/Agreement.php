<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'approved',
        'agreed_on',
        'file_uri',
        'hiring_request_id',
        'no_effect',

    ];

    public function HiringRequest()
    {
        return $this->belongsTo(HiringRequest::class);
    }
}
