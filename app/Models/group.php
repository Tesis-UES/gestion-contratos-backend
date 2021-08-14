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
        'professor_id'
    ];

    public function grupo()
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function academicLoad()
    {
        return $this->belongsTo(AcademicLoad::class,'academic_load_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
