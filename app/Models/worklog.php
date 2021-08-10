<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worklog extends Model
{
    use HasFactory;
    public $fillable = [
        'username',
        'actionLog',
        'relevance'
    ];
}
