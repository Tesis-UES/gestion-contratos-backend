<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicLoad extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'semester_id',
        'school_id'
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
