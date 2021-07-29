<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolAuthority extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'position',
        'startPeriod',
        'endPeriod',
        'school_id',
        'status'
    ];
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
