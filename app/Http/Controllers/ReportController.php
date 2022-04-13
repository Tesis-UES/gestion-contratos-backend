<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\{User, worklog, Semester, School, Person, HiringRequest, Employee, Agreement, HiringRequestDetail};
use Spatie\Permission\Models\Role;
use App\Http\Controllers\HiringRequestController;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Constants\ContractType;
use App\Exports\AmountExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\WorklogTrait;

class ReportController extends Controller
{
    use WorklogTrait;
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

    public function dashboard()
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

    public function getSpnpInfo($id)
    {
        $task = new HiringRequestController();
        $infoSpnp = $task->show($id);
        $total = 0;
        $candidatos = [];
        $cnd = [];
        foreach ($infoSpnp->details as $detail) {
            $subtotal = 0;
            foreach ($detail->hiringGroups as $group) {
                if ($group->period_hours != null) {
                    $subtotal += $group->hourly_rate * $group->period_hours;
                } else {
                    $subtotal += $group->hourly_rate * $group->work_weeks * $group->weekly_hours;
                }
            }
            array_push($candidatos, [
                'name' => $detail->person->first_name . ' ' . $detail->person->middle_name . ' ' . $detail->person->last_name,
                'subtotal' => $subtotal
            ]);
            $total += $subtotal;
        }
        $result = (object)['candidatos' => $candidatos];
        $result->total = $total;
        return $result;
    }

    public function getTaInfo($id)
    {
        $task = new HiringRequestController();
        $infota = $task->show($id);
        $total = 0;
        $candidatos = [];
        foreach ($infota->details as $detail) {
            $detail->fullName = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            if ($detail->period_hours != null) {
                $totalp = $detail->hourly_rate * $detail->period_hours;
            } else {
                $totalp = $detail->hourly_rate * $detail->work_weeks * $detail->weekly_hours;
            }
            array_push($candidatos, [
                'name' => $detail->person->first_name . ' ' . $detail->person->middle_name . ' ' . $detail->person->last_name,
                'subtotal' => $totalp
            ]);
            $total +=  $totalp;
        }
        $result = (object)['candidatos' => $candidatos];
        $result->total = $total;
        return $result;
    }

    public function getTiInfo($id)
    {
        $task = new HiringRequestController();
        $infoti = $task->show($id);
        $total = 0;
        $candidatos = [];
        foreach ($infoti->details as $detail) {
            $name = $detail->person->first_name . " " . $detail->person->middle_name . " " . $detail->person->last_name;
            $totalp = $detail->work_months * $detail->monthly_salary * $detail->salary_percentage;
            array_push($candidatos, [
                'name' => $name,
                'subtotal' => $totalp
            ]);
            $total +=  $totalp;
        }
        $result = (object)['candidatos' => $candidatos];
        $result->total = $total;
        return $result;
    }

    public function totalAmountsReport(Request $request)
    {

        $hiringRequests = HiringRequest::whereBetween('created_at', [$request->start_date,$request->end_date]);
        $fechaInicio = $request->start_date;
        $fechaFin = $request->end_date;
        switch ($request->type) {
            case 'escuela':
                $hiringRequests = $hiringRequests->where('school_id', $request->school_id)->get();
                break;
            case 'tcontrato':
                $hiringRequests = $hiringRequests->where('contract_type_id', $request->contract_type_id)->get();
                break;
            case 'tmodalidad':
                $hiringRequests = $hiringRequests->where('modality', $request->modality)->get();
                break;
            case 'escuela-contrato':
                $hiringRequests = $hiringRequests->where('school_id', $request->school_id)->where('contract_type_id', $request->contract_type_id)->get();
                break;
            default:
                $hiringRequests = $hiringRequests->get();
                break;
        }

        if ($hiringRequests->isEmpty()) {
            return response(
                ['mensaje'  => "No se encontraron solicitudes para las opciones de busqueda"],
                200
            );
        }

        foreach ($hiringRequests as $hr) {
            switch ($hr->contractType->name) {
                case ContractType::SPNP:
                    $info = $this->getSpnpInfo($hr->id);
                    $info->contractName = ContractType::SPNP;
                    $info->modality = $hr->modality;
                    $info->code = $hr->code;
                    $info->school = $hr->school->name;
                    $info->createDate = $hr->created_at;
                    break;

                case ContractType::TA:
                    $info = $this->getTaInfo($hr->id);
                    $info->contractName = ContractType::TA;
                    $info->modality = $hr->modality;
                    $info->code = $hr->code;
                    $info->school = $hr->school->name;
                    $info->createDate = $hr->created_at;
                    break;
                case ContractType::TI:
                    $info = $this->getTiInfo($hr->id);
                    $info->contractName = ContractType::TI;
                    $info->modality = $hr->modality;
                    $info->code = $hr->code;
                    $info->school = $hr->school->name;
                    $info->createDate = $hr->created_at;
                    break;

                default:
                    # code...
                    break;
            }
            $finalInfo[] = $info;
        }
        $reportInfo = (object)$finalInfo;
        $pdf    = PDF::loadView('reports.DetailHiringReport', compact('reportInfo', 'fechaInicio', 'fechaFin'));
        $report = base64_encode($pdf->stream());
        $this->RegisterAction("El usuario ha generado un reporte", "medium");
        return response(['report'  => $report], 200);
    }

    public function export($id)
    {
        $hr = HiringRequest::findOrFail($id);
        switch ($hr->contractType->name) {
            case ContractType::SPNP:
                $info = $this->getSpnpInfo($hr->id);
                return Excel::download(new AmountExport($info), $hr->code . '-Detalle de Montos.xlsx');
                break;

            case ContractType::TA:
                $info = $this->getTaInfo($hr->id);
                return Excel::download(new AmountExport($info), $hr->code . '-Detalle de Montos.xlsx');
                break;

            case ContractType::TI:
                $info = $this->getTiInfo($hr->id);
                return Excel::download(new AmountExport($info), $hr->code . '-Detalle de Montos.xlsx');
                break;
            default:
                # code...
                break;
        }
    }

    public function totalOfContractsByPerson(Request $request)
    {
        //get all persons by school_id
        $role = Role::where('name', 'Candidato')->first();
        $school = School::findOrFail($request->school_id);
        $pullCandidates = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->where('model_has_roles.role_id', '=', $role->id)->where('users.school_id', $request->school_id)->get();
        $candidatesPdf = [];
        if ($school->id == 9) {
            $escuela =  $school->name;
        } else {
            $escuela = "Escuela de " . $school->name;
        }
        foreach ($pullCandidates as $candidate) {

            if ($candidate->person != null) {
                $spnp = 0;
                $ta = 0;
                $ti = 0;
                foreach ($candidate->person->hiringRequestDetail as $detail) {
                    switch ($detail->hiringRequest->contractType->name) {
                        case ContractType::SPNP:
                            $spnp++;
                            break;
                        case ContractType::TA:
                            $ta++;
                            break;
                        case ContractType::TI:
                            $ti++;
                            break;

                        default:
                            # code...
                            break;
                    }
                }
                array_push($candidatesPdf, [
                    'name' => $candidate->person->first_name . ' ' . $candidate->person->middle_name . ' ' . $candidate->person->last_name,
                    'spnp' => $spnp,
                    'ta' => $ta,
                    'ti' => $ti
                ]);;
            }
        }

        $reportInfo = (object)$candidatesPdf;
        $pdf    = PDF::loadView('reports.DetailContractsByPerson', compact('reportInfo', 'escuela'));
        $report = base64_encode($pdf->stream());
        $this->RegisterAction("El usuario ha generado un reporte", "medium");
        return response(['report'  => $report], 200);
    }

    public function getAmountSPNP($id)
    {
        $detail =  HiringRequestDetail::findOrFail($id);
        $subtotal = 0;
        foreach ($detail->hiringGroups as $group) {
            if ($group->period_hours != null) {
                $subtotal += $group->hourly_rate * $group->period_hours;
            } else {
                $subtotal += $group->hourly_rate * $group->work_weeks * $group->weekly_hours;
            }
        }
        return [
            'hrCode' => $detail->hiringRequest->code,
            'hrModality' => $detail->hiringRequest->modality,
            'hrContractType' => $detail->hiringRequest->contractType->name,
            'subtotal' => $subtotal
        ];
    }

    public function getAmountTA($id)
    {
        $detail =  HiringRequestDetail::findOrFail($id);
        if ($detail->period_hours != null) {
            $totalp = $detail->hourly_rate * $detail->period_hours;
        } else {
            $totalp = $detail->hourly_rate * $detail->work_weeks * $detail->weekly_hours;
        }
        return [
            'hrCode' => $detail->hiringRequest->code,
            'hrModality' => $detail->hiringRequest->modality,
            'hrContractType' => $detail->hiringRequest->contractType->name,
            'subtotal' => $totalp
        ];
    }

    public function getAmountTI($id)
    {
        $detail =  HiringRequestDetail::findOrFail($id);
        $totalp = $detail->work_months * $detail->monthly_salary * $detail->salary_percentage;
        return [
            'hrCode' => $detail->hiringRequest->code,
            'hrModality' => $detail->hiringRequest->modality,
            'hrContractType' => $detail->hiringRequest->contractType->name,
            'subtotal' => $totalp
        ];
    }

    public function contractsAmountsByPerson(Request $request)
    {

        $hrds = HiringRequestDetail::where('person_id', $request->person_id)->get();

        $candidatesPdf = [];
        if (count($hrds) > 0) {

            foreach ($hrds as $hrd) {

                $name = $hrd->person->first_name . ' ' . $hrd->person->middle_name . ' ' . $hrd->person->last_name;
                switch ($hrd->hiringRequest->contractType->name) {
                    case ContractType::SPNP:
                        array_push($candidatesPdf, $this->getAmountSPNP($hrd->id));
                        break;
                    case ContractType::TA:
                        array_push($candidatesPdf, $this->getAmountTA($hrd->id));
                        break;
                    case ContractType::TI:
                        array_push($candidatesPdf, $this->getAmountTI($hrd->id));
                        break;

                    default:
                        # code...
                        break;
                }
            }

            $reportInfo = (object)$candidatesPdf;
            $pdf    = PDF::loadView('reports.ContractsAmountsByPerson', compact('reportInfo', 'name'));
            $report = base64_encode($pdf->stream());
            $this->RegisterAction("El usuario ha generado un reporte", "medium");
            return response(['report'  => $report], 200);
        } else {
            return response(['message' => 'No hay datos de contratos para la persona seleccionada'], 200);
        }
    }

    public function totalAmountByContracts(Request $request)
    {
      
        $hiringRequests = HiringRequest::whereBetween('created_at', [$request->start_date, $request->end_date])->where('school_id', $request->school_id)->get();
        $school = School::findOrFail($request->school_id);
        if ($school->id == 9) {
            $escuela =  $school->name;
        } else {
            $escuela = "Escuela de " . $school->name;
        }
        $fechaInicio = $request->start_date;
        $fechaFin = $request->end_date;
        if ($hiringRequests->isEmpty()) {
            return response(
                ['mensaje'  => "No se encontraron solicitudes para las opciones de busqueda"],
                200
            );
        }
            $spnpTotal = 0;
            $taTotal = 0;
            $tiTotal = 0;
            foreach ($hiringRequests as $hr) {
                switch ($hr->contractType->name) {
                    case ContractType::SPNP:
                        $info = $this->getSpnpInfo($hr->id);
                        $spnpTotal += $info->total;
                        break;
                    case ContractType::TA:
                        $info = $this->getTaInfo($hr->id);
                        $taTotal += $info->total;
                        break;
                    case ContractType::TI:
                        $info = $this->getTiInfo($hr->id);
                        $tiTotal += $info->total;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $pdf    = PDF::loadView('reports.TotalAmountByContracts', compact('spnpTotal', 'taTotal', 'tiTotal', 'escuela', 'fechaInicio', 'fechaFin'));
            $report = base64_encode($pdf->stream());
            $this->RegisterAction("El usuario ha generado un reporte", "medium");
            return response(['report'  => $report], 200);
        }
    }

