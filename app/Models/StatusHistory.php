<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;
    protected $table = 'status_history';
    protected $fillable = [
        'status_id',
        'hiring_request_id',
        'comments',
        ];

 
}
