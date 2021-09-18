<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'same_faculty',
        'person_id',
        'escalafon_id',
        'employee_type_id',
    ];

    public function staySchedules() {
        return $this->hasMany(StaySchedule::class);
    }
}
