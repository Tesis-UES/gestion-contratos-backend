<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,SoftDeletes;
    //Campos pertenecientes al modelo
    protected $fillable = [
        'code',
        'name',
        'school_id',
        'study_plan_id'
];

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }
}
