<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\{User, Worklog, Semester, School, Person, HiringRequest, Employee, Agreement};
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function adminDashboard()
    {
        //count all users
        $users = User::all()->count();
        //order users by school name
        $usersBySchool = User::select(DB::raw('count(school_id) as total'), 'schools.name as school_name')
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->groupBy('school_name')
            ->get();

        $usersByRole = User::select(DB::raw('count(model_has_roles.role_id) as total'), 'roles.name as role_name')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('role_name')
            ->get();

        //count all worklogs actions by relevance 
        $worklogs = Worklog::select(DB::raw('count(relevance) as total'), 'relevance')
            ->groupBy('relevance')
            ->get();

        //count all shcools registered
        $schools = School::all()->count();
        //count all semesters registered
        $semesters = Semester::all()->count();
        //count all employees registered by faculty
        $employeesByFaculty = Employee::select(DB::raw('count(faculty_id) as total'), 'faculties.name as faculty_name')
            ->join('faculties', 'employees.faculty_id', '=', 'faculties.id')
            ->groupBy('faculty_name')
            ->get();
        //get the last week worklogs actions by relevance
        $lastWeekWorklogs = Worklog::select(DB::raw('count(relevance) as total'), 'relevance')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->groupBy('relevance')
            ->get();

        return [
            'totalUsers' => $users,
            'usersBySchool' => $usersBySchool,
            'usersByRole' => $usersByRole,
            'worklogs' => $worklogs,
            'schools' => $schools,
            'semesters' => $semesters,
            'employeesByFaculty' => $employeesByFaculty,
            'lastWeekWorklogs' => $lastWeekWorklogs
        ];
    }

    public function rrhhDashboard()
    {
        //get the person count by validation status
        $personByValidationStatus = Person::select(DB::raw('count(status) as total'), 'status')->groupBy('status')->get();
        $agreementsCurrentYear = Agreement::select('id')->whereBetween('agreed_on', [now()->subYear(), now()])->count();
        $lastMonthAgreements = Agreement::select('id')->whereBetween('agreed_on', [now()->subMonth(), now()])->count();
        $lastSixMonthsAgreements = Agreement::select('id')->whereBetween('agreed_on', [now()->subMonths(6), now()])->count();
        //get the last year hiring requests by contract type
        $lastYearHiringRequests = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'contract_types.name as contract_type_name')
            ->join('contract_types', 'hiring_requests.contract_type_id', '=', 'contract_types.id')
            ->whereBetween('hiring_requests.created_at', [now()->subYear(), now()])
            ->groupBy('contract_type_name')
            ->get();
        $totalHiringRequests = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'contract_types.name as contract_type_name')
            ->join('contract_types', 'hiring_requests.contract_type_id', '=', 'contract_types.id')
            ->groupBy('contract_type_name')
            ->get();
        $lastYearHiringRequestsBySchool = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'schools.name as school_name')
            ->join('schools', 'hiring_requests.school_id', '=', 'schools.id')
            ->whereBetween('hiring_requests.created_at', [now()->subYear(), now()])
            ->groupBy('school_name')
            ->get();
        $totalHiringRequestsBySchool = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'schools.name as school_name')
            ->join('schools', 'hiring_requests.school_id', '=', 'schools.id')
            ->groupBy('school_name')
            ->get();

        $lastYearHiringRequestByModality = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'modality')
            ->whereBetween('hiring_requests.created_at', [now()->subYear(), now()])
            ->groupBy('modality')
            ->get();

        $totalHiringRequestByModality = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'modality')
            ->groupBy('modality')
            ->get();

        $persons = Person::all()->count();
        return [
            'registedCandidates' => $persons,
            'personByValidationStatus' => $personByValidationStatus,
            'agreementsCurrentYear' => $agreementsCurrentYear,
            'lastMonthAgreements' => $lastMonthAgreements,
            'lastSixMonthsAgreements' => $lastSixMonthsAgreements,
            'lastYearHiringRequests' => $lastYearHiringRequests,
            'totalHiringRequests' => $totalHiringRequests,
            'lastYearHiringRequestsBySchool' => $lastYearHiringRequestsBySchool,
            'totalHiringRequestsBySchool' => $totalHiringRequestsBySchool,
            'lastYearHiringRequestByModality' => $lastYearHiringRequestByModality,
            'totalHiringRequestByModality' => $totalHiringRequestByModality
        ];
    }

    public function DirectorDashboard($school)
    {
        $schoolCandidates = User::where('school_id', $school)->count();
        $totalSchoolHiringRequests = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'contract_types.name as contract_type_name')
            ->where('school_id', $school)
            ->join('contract_types', 'hiring_requests.contract_type_id', '=', 'contract_types.id')
            ->groupBy('contract_type_name')
            ->get();
        $totalSchoolHiringRequestByModality = DB::table('hiring_requests')->select(DB::raw('count(contract_type_id) as total'), 'modality')
            ->where('school_id', $school)
            ->groupBy('modality')
            ->get();

        return [
            'schoolCandidates' => $schoolCandidates,
            'totalSchoolHiringRequestsByContract' => $totalSchoolHiringRequests,
            'totalSchoolHiringRequestByModality' => $totalSchoolHiringRequestByModality,
        ];
    }

    public function Dashboard()
    {
        //get the login user 
        $user = Auth::user();
       
        switch ($user->getRoleNames()->first()) {
            case 'Administrador':
                return $this->adminDashboard();
                break;
            case 'Recursos Humanos':
                return $this->rrhhDashboard();
                break;
            case 'Decano':
                return array_merge($this->adminDashboard(), $this->rrhhDashboard());
                break;
            case 'Director Escuela':
               return $this->DirectorDashboard($user->school_id);
                break;

            default:
                # code...
                break;
        }

    }
}
