<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'escalafon_id',
    ];

    public function staySchedules() {
        return $this->hasMany(StaySchedule::class);
    }
}
