<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'journey_type',
        'faculty_id',
        'person_id',
        'escalafon_id',
        'employee_type_id',
    ];

    public function staySchedules() {
        return $this->hasMany(StaySchedule::class);
    }

    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }

    public function escalafon() {
        return $this->belongsTo(Escalafon::class);
    }

    public function employeeType() {
        return $this->belongsTo(EmployeeType::class);
    }
}
