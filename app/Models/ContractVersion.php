<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractVersion extends Model
{
    use HasFactory;

    protected $fillabe = [
        'name',
        'version',
        'active',
        'request_detail_id',
    ];

    public function HiringRequestDetail()
    {
        return $this->belongsTo(HiringRequestDetail::class);
    }
}
