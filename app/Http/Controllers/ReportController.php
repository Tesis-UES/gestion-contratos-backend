<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\{User,Worklog,Semester,School,Person,HiringRequest,Employee};

class ReportController extends Controller
{
    public function adminDashboard()
    {
        //count all users
        $users = User::all()->count();
        //order users by school name
        $usersBySchool = User::select(DB::raw('count(school_id) as total'),'schools.name as school_name')
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->groupBy('school_name')
            ->get();

        $usersByRole = User::select(DB::raw('count(model_has_roles.role_id) as total'),'roles.name as role_name')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('role_name')
            ->get();
        
        //count all worklogs actions by relevance 
        $worklogs = Worklog::select(DB::raw('count(relevance) as total'),'relevance')
            ->groupBy('relevance')
            ->get();
        
        //count all shcools registered
        $schools = School::all()->count(); 
        //count all semesters registered
        $semesters = Semester::all()->count();
        //count all employees registered by faculty
        $employeesByFaculty = Employee::select(DB::raw('count(faculty_id) as total'),'faculties.name as faculty_name')
            ->join('faculties', 'employees.faculty_id', '=', 'faculties.id')
            ->groupBy('faculty_name')
            ->get();
        //get the last week worklogs actions by relevance
        $lastWeekWorklogs = Worklog::select(DB::raw('count(relevance) as total'),'relevance')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->groupBy('relevance')
            ->get();

        return ['totalUsers' => $users, 'usersBySchool' => $usersBySchool, 'usersByRole' => $usersByRole, 'worklogs' => $worklogs, 'schools' => $schools, 'semesters' => $semesters, 'employeesByFaculty' => $employeesByFaculty, 'lastWeekWorklogs' => $lastWeekWorklogs];
    }

    public function rrhuDashboard(){
        //get the person count by validation status
        $personByValidationStatus = Person::select(DB::raw('count(validation_status) as total'),'validation_status')
            ->groupBy('validation_status')
            ->get();
    }
}
