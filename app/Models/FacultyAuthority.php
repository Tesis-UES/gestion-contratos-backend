<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FacultyAuthority extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'position',
        'startPeriod',
        'endPeriod',
        'faculty_id',
        'status'
    ];
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
