<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EmployeeType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['pivot'];

    public function employees() {
        return $this->belongsToMany(Employee::class, 'employee_employee_types');
    }
}
