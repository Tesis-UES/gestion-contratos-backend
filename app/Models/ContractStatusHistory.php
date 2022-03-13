<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_status_id',
        'hiring_request_detail_id',
    ];
}
