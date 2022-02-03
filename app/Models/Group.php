<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'number',
        'group_type_id',
        'academic_load_id',
        'course_id',
        'people_id',
        'status',
        'modality',
    ];

    public function grupo()
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function candidato()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }


    public function academicLoad()
    {
        return $this->belongsTo(AcademicLoad::class, 'academic_load_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function hiringRequestDetails()
    {
        return $this->belongsToMany(HiringRequestDetails::class, 'hiring_groups');
    }
}
