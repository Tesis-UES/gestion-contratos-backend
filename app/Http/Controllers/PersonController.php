<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\EmployeeType;
use App\Models\Escalafon;
use App\Models\Faculty;
use App\Models\Person;
use App\Models\PersonValidation;
use App\Models\{PersonChange, CentralAuthority, StaySchedule};
use Illuminate\Http\Request;
use App\Http\Traits\{WorklogTrait, ValidationTrait};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Luecano\NumeroALetras\NumeroALetras;
use DB;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use App\Http\Controllers\HiringRequestController;
use Mail;
use App\Mail\ValidationDocsNotification;


class PersonController extends Controller
{
    use WorklogTrait, ValidationTrait;

    public function allCandidates()
    {
        $result = Person::all();
        foreach ($result as $rest) {
            $candidate = [
                'id'        => $rest->id,
                'name'      => $rest->first_name . " " . $rest->middle_name,
                'last_name' => $rest->last_name,
                'status'    => $rest->status,
                'school_id' => $rest->user->school_id,
                'school_name'   => $rest->user->school->name
            ];
            $candiates[] = $candidate;
        }

        $this->RegisterAction('El usuario ha consultado el catalogo de candidatos registrados en el sistema');
        return response($candiates, 200);
    }

    public function allCandidatesProfessor()
    {
        //ESTA FUNCION PODRA SER MODIFICADA MAS ADELANTE DEPENDIENDO DE LOS REQUERIMIENTOS
        $result = Person::all();
        foreach ($result as $rest) {
            $candidate = [
                'id'        => $rest->id,
                'name'      => $rest->first_name . " " . $rest->middle_name . " " . $rest->last_name,
            ];
            $candiates[] = $candidate;
        }

        $this->RegisterAction('El usuario ha consultado el catalogo de candidatos registrados en el sistema para asignar a un grupo');
        return response($candiates, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'            => 'required|string|max:120',
            'middle_name'           => 'required|string|max:120',
            'last_name'             => 'required|string|max:120',
            'civil_status'          => 'required|string|max:120',
            'know_as'               => 'string|max:120',
            'married_name'          => 'string|max:120',
            'birth_date'            => 'required|date|before:today',
            'gender'                => 'required|string|max:120',
            'telephone'             => 'required|string|max:120',
            'alternate_telephone'   => 'string|max:120',
            'alternate_mail'        => 'string|max:120',
            'address'               => 'required|string|max:120',
            'nationality'           => 'required|string|max:120',
            'professional_title'    => 'required|string|max:120',
            'bank_account_type'     => 'string:max:100',
            'bank_id'               => 'integer|gte:1',
            'bank_account_number'   => 'string|max:120',
            'is_employee'           => 'required|boolean',
            'is_nationalized'       => 'required|boolean',
            'other_title'           => 'required|boolean',
        ]);

        if ($request->input('bank_id') != null) {
            Bank::findOrFail($request->input('bank_id'));
        }

        if ($request->input('nationality') == 'El Salvador') {

            if ($request->input('is_nationalized') == true) {
                $request->validate([
                    'city'                  => 'required|string|max:120',
                    'department'            => 'required|string|max:120',
                    'nup'                   => 'required|string|max:120',
                    'isss_number'           => 'required|max:120',
                    'resident_card_number'  => 'required|string|max:120',
                    'resident_expiration_date'   => 'required|date|after:today',
                    'nit_number'            => ' nullable|string|max:120',
                ]);
            } else {
                $request->validate([
                    'city'                  => 'required|string|max:120',
                    'department'            => 'required|string|max:120',
                    'nup'                   => 'required|string|max:120',
                    'isss_number'           => 'required|max:120',
                    'dui_number'            => 'required|string|max:120',
                    'dui_expiration_date'   => 'required|date|after:today',
                    'nit_number'            => 'nullable|string|max:120',
                ]);
            }
        } else {
            $request->validate(['passport_number' => 'required|string|max:120']);
        }

        if ($request->input('is_employee') == true) {
            $employeeFields = $request->validate([
                'partida'           => 'string|max:50',
                'sub_partida'       => 'string|max:50',
                'journey_type'      => ['required', Rule::in(['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo', 'tiempo-parcial', 'tiempo-eventual'])],
                'faculty_id'        => 'required|integer|gte:1',
                'escalafon_id'      => 'required|integer|gte:1',
                'employee_types'    => 'required|array|min:1',
                'employee_types.*'  => 'required|integer|distinct|gte:1',
            ]);

            Escalafon::findOrFail($employeeFields['escalafon_id']);
            Faculty::findOrFail($employeeFields['faculty_id']);
            foreach ($employeeFields['employee_types'] as $typeId) {
                $employeeTypes[] = EmployeeType::findOrFail($typeId);
            }
        }
        if ($request->input('other_title') == true) {
            $request->validate([
                'other_title_name'                 => 'required|string|max:120',
            ]);
        }

        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->first();
        if ($person) {
            return response(['message' => "El usuario ya ha registrado sus datos personales",], 400);
        }

        $newPerson = new Person($request->all());
        $newPerson->user_id = $user->id;
        if ($newPerson->nationality == 'El Salvador') {

            if ($request->input('is_nationalized') == true) {
                $newPerson->resident_card_text = $this->carnetToText($newPerson->resident_card_number);
                if($newPerson->nit_number != null){
                    $newPerson->nit_text = $this->nitToText($newPerson->nit_number);
                }else{
                    $newPerson->nit_text = "N/A"; 
                }
               
            } else {
                $newPerson->dui_text = $this->duiToText($newPerson->dui_number);
                if($newPerson->nit_number != null){
                    $newPerson->nit_text = $this->nitToText($newPerson->nit_number);
                }else{
                    $newPerson->nit_text = "N/A"; 
                }
            }
        }
        $newPerson->save();

        PersonChange::create(['person_id' => $newPerson->id, 'change' => "Se registraron los datos personales generales."]);
        $this->RegisterAction("El usuario he registrado sus datos personales generales", "medium");

        if ($request->input('is_employee') == true) {
            $newEmployee = $newPerson->employee()->create($employeeFields);
            $newEmployee->employeeTypes()->saveMany($employeeTypes);

            PersonChange::create(['person_id' => $newPerson->id, 'change' => "Se registraron los datos del profesor."]);
            $this->RegisterAction("El usuario he registrado como profesor", "medium");
        }

        $personValidation = new PersonValidation(['person_id' => $newPerson->id]);
        $personValidation->save();

        return response(['person' => $newPerson], 201);
    }

    public function show($id)
    {
        $person = Person::where('id', $id)
            ->with('bank')
            ->with('employee', function ($query) {
                $query->with(['faculty', 'escalafon', 'employeeTypes']);
            })
            ->firstOrFail();

        return response(['person' => $person,], 200);
    }

    public function showMyInfo()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)
            ->with('bank')
            ->with('employee', function ($query) {
                $query->with(['faculty', 'escalafon', 'employeeTypes']);
            })
            ->firstOrFail();

        return response($person, 200);
    }

    public function hasRegistered()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->first();
        if ($person != null) {
            return response(['has_registered' => true], 200);
        }
        return response(['has_registered' => false], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name'            => 'required|string|max:120',
            'middle_name'           => 'required|string|max:120',
            'last_name'             => 'required|string|max:120',
            'civil_status'          => 'required|string|max:120',
            'know_as'               => 'string|max:120',
            'married_name'          => 'string|max:120',
            'birth_date'            => 'required|date|before:today',
            'gender'                => 'required|string|max:120',
            'telephone'             => 'required|string|max:120',
            'alternate_telephone'   => 'string|max:120',
            'alternate_mail'        => 'string|max:120',
            'address'               => 'required|string|max:120',
            'nationality'           => 'required|string|max:120',
            'professional_title'    => 'required|string|max:120',
            'bank_id'               => 'integer|gte:1',
            'bank_account_type'     => 'string:max:100',
            'bank_account_number'   => 'string|max:120',
            'is_employee'           => 'required|boolean',
            'is_nationalized'       => 'required|boolean',
            'other_title'           => 'required|boolean',
        ]);

        if ($request->input('bank_id') != null) {
            Bank::findOrFail($request->input('bank_id'));
        }

        if ($request->input('nationality') == 'El Salvador') {
            if ($request->input('is_nationalized') == true) {
                $request->validate([
                    'city'                  => 'required|string|max:120',
                    'department'            => 'required|string|max:120',
                    'nup'                   => 'required|string|max:120',
                    'isss_number'           => 'required|max:120',
                    'resident_card_number'  => 'required|string|max:120',
                    'resident_expiration_date'   => 'required|date|after:today',
                    'nit_number'            => 'nullable|string|max:120',
                ]);
            } else {
                $request->validate([
                    'city'                  => 'required|string|max:120',
                    'department'            => 'required|string|max:120',
                    'nup'                   => 'required|string|max:120',
                    'isss_number'           => 'required|max:120',
                    'dui_number'            => 'required|string|max:120',
                    'dui_expiration_date'   => 'required|date|after:today',
                    'nit_number'            => 'nullable|string|max:120',
                ]);
            }
        } else {
            $request->validate(['passport_number' => 'required|string|max:120']);
        }

        if ($request->input('is_employee') == true) {
            $employeeFields = $request->validate([
                'partida'           => 'integer|max:50',
                'sub_partida'       => 'integer|max:50',
                'journey_type'      => ['required', Rule::in(['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo', 'tiempo-parcial', 'tiempo-eventual'])],
                'faculty_id'        => 'required|integer|gte:1',
                'escalafon_id'      => 'required|integer|gte:1',
                'employee_types'    => 'required|array|min:1',
                'employee_types.*'  => 'required|integer|gte:1',
            ]);

            Escalafon::findOrFail($employeeFields['escalafon_id']);
            Faculty::findOrFail($employeeFields['faculty_id']);
            foreach ($employeeFields['employee_types'] as $typeId) {
                $employeeTypes[] = EmployeeType::findOrFail($typeId);
            }
        }
        if ($request->input('other_title') == true) {
            $request->validate([
                'other_title_name'                 => 'required|string|max:120',
            ]);
        }

        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();

        $person->update($request->all());
        if ($person->nationality == 'El Salvador') {
            if ($request->input('is_nationalized') == true) {
                $person->resident_card_text = $this->carnetToText($person->resident_card_number);
                if($person->nit_number != null){
                    $person->nit_text = $this->nitToText($person->nit_number);
                }else{
                    $person->nit_text = "N/A"; 
                }
            } else {
                $person->dui_text = $this->duiToText($person->dui_number);
                if($person->nit_number != null){
                    $person->nit_text = $this->nitToText($person->nit_number);
                }else{
                    $person->nit_text = "N/A"; 
                }
            }
        }
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se actualizo la información general de datos personales"]);
        $this->RegisterAction("El usuario ha actualizado sus datos personales generales", "medium");

        if ($request->input('is_employee') == true) {
            $employee = $person->employee;
            if ($employee == null) {
                $newEmployee = $person->employee()->create($employeeFields);
                $newEmployee->employeeTypes()->saveMany($employeeTypes);

                PersonChange::create(['person_id' => $person->id, 'change' => "Se registraron los datos del profesor."]);
                $this->RegisterAction("El usuario he registrado como profesor", "medium");
            } else {
                $employee->update($employeeFields);
                $employee->employeeTypes()->detach();
                $employee->employeeTypes()->saveMany($employeeTypes);

                PersonChange::create(['person_id' => $person->id, 'change' => "Se actualizaron los datos del profesor."]);
                $this->RegisterAction("El usuario ha actualizado sus datos de profesor", "medium");
            }
        }

        $person->refresh();
        return response(['person' => $person], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $person->delete();
        $this->RegisterAction("Se ha eleminado la informacion general del usuario", "medium");
        return response(null, 204);
    }

    public function storeMenu(Request $request)
    {
        $type = $request['type'];
        switch ($type) {
            case 'dui':
                $person =  $this->storeDui($request);
                break;

            case 'nit':
                $person =  $this->storeNit($request);
                break;

            case 'banco':
                $person =  $this->storeBank($request);
                break;

            case 'cv':
                $person =  $this->storeCurriculum($request);
                break;

            case 'titulo':
                $person =  $this->storeTitle($request);
                break;

            case 'permiso':
                $person =  $this->storePermission($request);
                break;
            case 'pass':
                $person =  $this->storePassport($request);
                break;

            case 'carnet':
                $person =  $this->storeResident($request);
                break;

            case 'otro_titulo':
                $person =  $this->storeOtherTitle($request); 
                break;
            
            case 'declaracion':
                    $person =  $this->storeStatement($request); 
                    break;
            default:
                # code...
                break;
        }
        $this->updatePersonStatus($person);
        return response(['person' => $person,], 200);
    }

    public function storeDui(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-DUI.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el DUI"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del DUI", "medium");
        return $person;
    }

    public function storeResident(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('carnet');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-CARNET_RESIDENTE.pdf";
        $person->resident_card = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el Carnet de Residencia"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del Carnet de Residencia", "medium");
        return $person;
    }

    public function storePassport(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('pass');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-PASAPORTE.pdf";
        $person->passport = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el Pasaporte"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del Pasaporte", "medium");
        return  $person;
    }

    public function storeNit(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-NIT.pdf";
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el NIT"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del NIT", "medium");
        return $person;
    }

    public function storeBank(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('banco');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-CuentaDeBanco.pdf";
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene la Cuenta Bancaria"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su cuenta de banco", "medium");
        return $person;
    }

    public function storeTitle(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('titulo');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-Titulo.pdf";
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el Titulo Univesitario"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su titulo Universitario", "medium");
        return  $person;
    }

    public function storeOtherTitle(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('otro_titulo');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-Titulo_extra.pdf";
        $person->other_title_doc = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el titulo Extra (Doctorado/Maestria)"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su  titulo Extra (Doctorado/Maestria)", "medium");
        return  $person;
    }

    public function storeCurriculum(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('cv');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-Curriculum.pdf";
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su curriculum", "medium");
        return  $person;
    }

    public function storePermission(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('permiso');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-permission.pdf";
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene el Permiso de laborar en la facultad"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo");
        return $person;
    }

    public function storeStatement(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('declaracion');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-DeclaracionJurada.pdf";
        $person->statement = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se subio y guardo el archivo que contiene la declaracion jurada"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su Declaracion Jurada", "medium");
        return  $person;
    }

    public function updateMenu(Request $request)
    {

        $type = $request['type'];
        switch ($type) {
            case 'dui':
                $person =  $this->updateDui($request);
                break;

            case 'nit':
                $person =  $this->updateNit($request);
                break;

            case 'banco':
                $person =  $this->updateBank($request);
                break;

            case 'cv':
                $person =  $this->updateCurriculum($request);
                break;

            case 'titulo':
                $person =  $this->updateTitle($request);
                break;

            case 'permiso':
                $person =  $this->updatePermisssion($request);
                break;
            case 'pass':
                $person =  $this->updatePassport($request);
                break;
            case 'carnet':
                $person =  $this->updateResident($request);
                break;

            case 'otro_titulo':
                $person =  $this->updateOtherTitle($request);  
                break;
            
            case 'declaracion':
                    $person =  $this->updateStatement($request);  
                    break;
            default:
                # code...
                break;
        }
        $this->updatePersonStatus($person);
        $this->hrUpdateDocNotification($request['type']);
        return response(['person' => $person,], 200);
    }

    public function hrUpdateDocNotification($doc)
    {
        $task = new HiringRequestController;
        $emails = $task->getHRMail();
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $nombrePersona = $person->first_name . " " . $person->middle_name . " " . $person->last_name;
        switch ($doc) {
            case 'dui':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Documento Unico de Identidad DUI, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados.";
                break;

            case 'nit':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su NIT, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;

            case 'banco':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Cuenta de Banco, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;

            case 'cv':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Curriculum, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;

            case 'titulo':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Titulo Profesional, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;

            case 'permiso':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Permiso de Trabajo, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;
            case 'pass':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Pasaporte, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados. ";
                break;
            case 'carnet':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Carnet de Residencia, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados ";
                break;

            case 'otro_titulo':
                $mensaje = "El usuario " . $nombrePersona . " ha actualizado su Segundo Titulo, por favor revisar y validar dicho documento para que el candidato tenga todos sus documentos validados ";
                break;
            default:
                # code...
                break;
        }
        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensaje, 'HrUpdateDoc'));
                $mensaje = 'La solicitud se ha enviado a RRHH para validación y se envió el correo de notificación al responsable de RRHH';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'La solicitud se ha enviado a RRHH para validación, pero no se envió el correo de notificación al responsable de RRHH';
            }
        }
    }

    public function updateDui(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-DUI.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->dui);
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el DUI"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'dui_readable'      =>  false,
            'dui_name'          =>  false,
            'dui_number'        =>  false,
            'dui_profession'    =>  false,
            'dui_civil_status'  =>  false,
            'dui_birth_date'    =>  false,
            'dui_unexpired'     =>  false,
            'dui_address'       =>  false,
            'dui'               =>  false
        ]);
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del DUI", "medium");
        return $person;
    }

    public function updateResident(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('carnet');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-CARNET_RESIDENTE.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->resident_card);
        $person->resident_card = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el carnet de residente"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'carnet'            => false,
            'carnet_readable'   => false,
            'carnet_name'       => false,
            'carnet_number'     => false,
            'carnet_unexpired'  => false
        ]);
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del Carnet de residente", "medium");
        return $person;
    }

    public function updateNit(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-NIT.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->nit);
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el NIT"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'nit_readable'      =>  false,
            'nit_name'          =>  false,
            'nit_number'        =>  false,
            'nit'               =>  false,

        ]);
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del NIT", "medium");
        return  $person;
    }

    public function updateBank(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('banco');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-CuentaDeBanco.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->bank_account);
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene la cuenta bancaria"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'bank_readable'        =>  false,
            'bank_number'          =>  false,
            'bank'          =>  false,
        ]);
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su cuenta de banco", "medium");
        return $person;
    }

    public function updateTitle(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('titulo');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-Titulo.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->professional_title_scan);
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el Titulo Universitario"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'title_readable'        =>  false,
            'title_mined'           =>   false,
            'title_apostilled'      =>   false,
            'title_apostilled_readable'         =>   false,
            'title_authentic'                   =>   false,
            'title'                   =>   false,
        ]);
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su titulo Universitario", "medium");
        return  $person;
    }

    public function updateOtherTitle(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('otro_titulo');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-Titulo_extra.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->other_title_doc);
        $person->other_title_doc = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene  el titulo Extra (Doctorado/Maestria)"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'other_title'                       => false,
            'other_title_readable'              => false,
            'other_title_apostilled'            => false,
            'other_title_apostilled_readable'   => false,
            'other_title_authentic'             => false
        ]);
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su titulo Extra (Doctorado/Maestria)", "medium");
        return  $person;
    }

    public function updateCurriculum(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('cv');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-curriculum.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->curriculum);
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'curriculum_readable'      => false,
            'curriculum'      => false,
        ]);
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su curriculum", "medium");
        return $person;
    }

    public function updateStatement(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('declaracion');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-DeclaracionJurada.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->statement);
        $person->statement = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene la declaracion jurada"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'statement_readable'      => false,
            'statement'      => false,
        ]);
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su declaracion jurada", "medium");
        return $person;
    }

    public function updatePermisssion(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('permiso');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-permission.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->work_permission);
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el permiso de trabajo"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'work_permission_readable'      =>  false,
            'work'      =>  false,
        ]);
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo");
        return  $person;
    }

    public function updatePassport(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $file = $request->file('pass');
        $nombre_archivo = $person->first_name . " " . $person->middle_name . " " . $person->last_name . "-PASAPORTE.pdf";
        //Se elimina el archivo antiguo
        \Storage::disk('personalFiles')->delete($person->passport);
        $person->passport = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id' => $person->id, 'change' => "Se Actualizo el archivo que contiene el Pasaporte"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $personValidations = $person->personValidations;
        $personValidations->update([
            'passport_readable'      =>  false,
            'passport_name'          =>  false,
            'passport_number'        =>  false,
            'passport'        =>  false,
        ]);
        $this->RegisterAction("El usuario ha Actualizado el  archivo pdf que contiene su Pasaporte");
        return  $person;
    }

    public function getMenu($type)
    {

        switch ($type) {
            case 'dui':
                $person =  $this->getDui();
                break;

            case 'nit':
                $person =  $this->getNit();
                break;

            case 'banco':
                $person =  $this->getBank();
                break;

            case 'cv':
                $person =  $this->getCurriculum();
                break;

            case 'titulo':
                $person =  $this->getTitle();
                break;

            case 'permiso':
                $person =  $this->getPermission();
                break;
            case 'pass':
                $person =  $this->getPassport();
                break;
            case 'carnet':
                $person =  $this->getResident();
                break;

            case 'otro_titulo':
                $person =  $this->getOtherTitle();
                break;
            case 'declaracion':
                    $person =  $this->getStatement();
                    break;
            default:
                # code...
                break;
        }
        return response(['person' => $person], 200);
    }


    public function getDui()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'dui_readable'      =>  $person->personValidations->dui_readable,
            'dui_name'          =>  $person->personValidations->dui_name,
            'dui_number'        =>  $person->personValidations->dui_number,
            'dui_profession'    =>  $person->personValidations->dui_profession,
            'dui_civil_status'  =>  $person->personValidations->dui_civil_status,
            'dui_birth_date'    =>  $person->personValidations->dui_birth_date,
            'dui_unexpired'     =>  $person->personValidations->dui_unexpired,
            'dui_address'       =>  $person->personValidations->dui_address
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->dui));
        return [
            'pdfDui' => $pdf,
            'validations' => $validations
        ];
    }

    public function getResident()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [

            'carnet_readable'   => $person->personValidations->carnet_readable,
            'carnet_name'       => $person->personValidations->carnet_name,
            'carnet_number'     => $person->personValidations->carnet_number,
            'carnet_unexpired'  => $person->personValidations->carnet_unexpired,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->resident_card));
        return [
            'pdfResident' => $pdf,
            'validations' => $validations
        ];
    }

    public function getPassport()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'passport_readable'      =>  $person->personValidations->passport_readable,
            'passport_name'          =>  $person->personValidations->passport_name,
            'passport_number'        =>  $person->personValidations->passport_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->passport));
        return [
            'pdfPassport' => $pdf,
            'validations' => $validations
        ];
    }

    public function getNit()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'nit_readable'      =>  $person->personValidations->nit_readable,
            'nit_name'          =>  $person->personValidations->nit_name,
            'nit_number'        =>  $person->personValidations->nit_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->nit));
        return [
            'pdfNit' => $pdf,
            'validations' => $validations
        ];
    }

    public function getBank()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'bank_readable'      =>  $person->personValidations->bank_readable,
            'bank_number'          =>  $person->personValidations->bank_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->bank_account));
        return [
            'pdfBank' => $pdf,
            'validations' => $validations
        ];
    }

    public function getTitle()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        if ($person->nationality == 'El Salvador') {
            $validations = [
                'title_readable'      =>  $person->personValidations->title_readable,
                'title_mined'      =>  $person->personValidations->title_mined,
            ];
        } else {
            $validations = [
                'title_readable'      =>  $person->personValidations->title_readable,
                'title_apostilled'      =>  $person->personValidations->title_apostilled,
                'title_apostilled_readable'      =>  $person->personValidations->title_apostilled_readable,
                'title_authentic'      =>  $person->personValidations->title_authentic,
            ];
        }

        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->professional_title_scan));
        return [
            'pdfTitle' => $pdf,
            'validations' => $validations
        ];
    }

    public function getOtherTitle()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();

        $validations = [
            'other_title_readable'              => $person->personValidations->other_title_readable,
            'other_title_apostilled'            => $person->personValidations->other_title_apostilled,
            'other_title_apostilled_readable'   => $person->personValidations->other_title_apostilled_readable,
            'other_title_authentic'             => $person->personValidations->other_title_authentic

        ];


        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->other_title_doc));
        return [
            'pdfOtherTitle' => $pdf,
            'validations' => $validations
        ];
    }

    public function getCurriculum()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'curriculum_readable'      =>  $person->personValidations->curriculum_readable,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->curriculum));
        return [
            'pdfCurriculum' => $pdf,
            'validations' => $validations
        ];
    }

    public function getPermission()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'work_permission_readable'      =>  $person->personValidations->work_permission_readable,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->work_permission));
        return [
            'pdfPermission' => $pdf,
            'validations' => $validations
        ];
    }

    public function getStatement()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $validations = [
            'statement_readable'      =>  $person->personValidations->statement_readable,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->statement));
        return [
            'pdfPermission' => $pdf,
            'validations' => $validations
        ];
    }

    public function duiToText($dui)
    {
        $formatter = new NumeroALetras();
        if (substr($dui, 0, -9) == 0 && substr($dui, 1, -8) == !0) {
            return  $textDui = "CERO " . $formatter->toString(substr($dui, 0, -2)) . "GUION " . $formatter->toString(substr($dui, -1)) . "";
        }
        if (substr($dui, 1, -8) == 0 && substr($dui, 2, -7) == !0) {
            return $textDui = "CERO CERO " . $formatter->toString(substr($dui, 0, -2)) . "GUION " . $formatter->toString(substr($dui, -1)) . "";
        }
        if (substr($dui, 2, -7) == 0 && substr($dui, 3, -6) == !0) {
            return $textDui = "CERO CERO CERO " . $formatter->toString(substr($dui, 0, -2)) . "GUION " . $formatter->toString(substr($dui, -1)) . "";
        } else {
            return $textDui = "CERO CERO CERO CERO " . $formatter->toString(substr($dui, 0, -2)) . "GUION " . $formatter->toString(substr($dui, -1)) . "";
        }
    }

    public function carnetToText($carnet)
    {
        $formatter = new NumeroALetras();
        return  $textCarnet = $formatter->toString($carnet);
    }

    public function nitToText($nit)
    {
        $formatter = new NumeroALetras();
        $nitParts = explode("-", $nit);

        if (substr($nitParts[0], 0, -3) == 0 && substr($nitParts[0], 1, -2) == !0) {
            $part1 = "CERO " . $formatter->toString($nitParts[0]) . "";
        } else {
            $part1 = "CERO CERO " . $formatter->toString($nitParts[0]) . "";
        }

        if (substr($nitParts[1], 0, -5) == 0 && substr($nitParts[1], 1, -4) == !0) {
            $part2 = "CERO " . $formatter->toString($nitParts[1]) . "";
        }
        if (substr($nitParts[1], 1, -4) == 0) {
            $part2 = "CERO CERO " . $formatter->toString($nitParts[1]) . "";
        } else {
            $part2 = $formatter->toString($nitParts[1]);
        }

        if (substr($nitParts[2], 0, -2) == 0) {
            $part3 = "CERO " . $formatter->toString($nitParts[2]) . "";
        }
        if (substr($nitParts[2], 1, -1) == 0 && substr($nitParts[2], 0, -2) == 0) {
            $part3 = "CERO CERO " . $formatter->toString($nitParts[2]) . "";
        } else if (substr($nitParts[2], 0, -2) == !0 && substr($nitParts[2], 0, -2) == !0) {
            $part3 = $formatter->toString($nitParts[2]);
        }
        $part4 =  $formatter->toString($nitParts[3]);
        return $textNIt = "" . $part1 . "-" . $part2 . "-" . $part3 . "-" . $part4 . "";
    }

    public function myChanges()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $changes = PersonChange::where('person_id', $person->id)->get();
        return response(['changes' => $changes,], 200);
    }

    public function getDocumentsByCase($id = null)
    {

        if ($id == null) {
            $user = Auth::user();
            $person = Person::where('user_id', $user->id)->firstOrFail();
        } else {
            $person = Person::where('id', $id)->firstOrFail();
        }
        if ($person->employee == null) {
            //Si no es empleado verificamos que sea nacional o extanjero
            if ($person->nationality == 'El Salvador') {
                $archivos = ['cv', 'banco', 'titulo','declaracion'];
                if ($person->is_nationalized == true) {
                    array_push($archivos, 'carnet');
                } else {
                    array_push($archivos, 'dui');
                }
                if ($person->other_title == true) {
                    array_push($archivos, 'otro_titulo');
                }
                if($person->nit_number != null){
                    array_push($archivos, 'nit');
                }
                
            } else {
                //EXTRANJERO
                $archivos =  ['cv', 'banco', 'titulo', 'pass','declaracion'];
                if ($person->other_title == true) {
                    array_push($archivos, 'otro_titulo');
                }
            }
        } else {
            //Candidato - Trabajador
            if ($person->nationality == 'El Salvador') {
                //Candidato - Trabajador - Nacional
                $archivos = ['cv', 'banco',  'titulo','declaracion'];
                if ($person->is_nationalized == true) {
                    array_push($archivos, 'carnet');
                } else {
                    array_push($archivos, 'dui');
                }
                if ($person->other_title == true) {
                    array_push($archivos, 'otro_titulo');
                }
                if (!($person->employee->faculty_id == 1)) {
                    array_push($archivos, 'permiso');
                }
                if($person->nit_number != null){
                    array_push($archivos, 'nit');
                }
            } else {
                //Candidato - Trabajador - Internacional 
                $archivos = ['cv', 'banco',  'titulo', 'pass','declaracion'];
                if (!($person->employee->faculty_id == 1)) {
                    array_push($archivos, 'permiso');
                }
                if ($person->other_title == true) {
                    array_push($archivos, 'otro_titulo');
                }
            }
        }
        if ($id == null) {
            return response(['archivos' => $archivos], 200);
        } else {
            return $archivos;
        }
    }



    public  function mergePersonalDoc($id)
    {
        $respuesta =   $this->getDocumentsByCase($id);
        $person = Person::where('id', $id)->firstOrFail();
        $m = new Merger();
        foreach ($respuesta as $archivo) {
            switch ($archivo) {
                case 'cv':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->curriculum));
                    break;

                case 'dui':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->dui));
                    break;
                case 'nit':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->nit));
                    break;
                case 'carnet':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->resident_card));
                    break;
                case 'banco':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->bank_account));
                    break;
                case 'titulo':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->professional_title_scan));
                    break;
                case 'otro_titulo':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->other_title_doc));
                    break;
                case 'permiso':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->work_permission));
                    break;
                case 'pass':
                    $m->addRaw(\Storage::disk('personalFiles')->get($person->passport));
                    break;
                case 'declaracion':
                        $m->addRaw(\Storage::disk('personalFiles')->get($person->statement));
                        break;

                default:
                    # code...
                    break;
            }
        }
        $createdPdf = $m->merge();
        $namePdf = $person->first_name . '_' . $person->last_name . '_' . $person->id . '.pdf';
        $person->merged_docs =  $namePdf;
        \Storage::disk('personDocsMerged')->put($namePdf, $createdPdf);
        $person->save();
        return 'exito';
    }

    public function calculaedad($fechanacimiento)
    {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }


    public function getCandidates(Request $request)
    {
        $candidates = Person::select('id', DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as name"));
        switch ($request->hiringType) {
            case 'SPNP':
                $candidates = $candidates->where('is_employee', false)->where('status', 'Validado');
                break;
            case 'RP':
                $candidates;
                break;
            default:
                $candidates = $candidates->where('is_employee', true)->where('status', 'Validado');
                break;
        }
        $search = $request->search;
        if ($search != null) {
            $candidates->where(function ($query) use ($search) {
                $query->where('first_name', 'ilike', '%' . $search . '%')
                    ->orWhere('middle_name', 'ilike', '%' . $search . '%')
                    ->orWhere('last_name', 'ilike', '%' . $search . '%');
            });
        }
        $candidates = $candidates->get();
        return response(['candidates' => $candidates], 200);
    }

    public function getInfoCandidate($person)
    {
        $person = Person::findOrFail($person);
        $lastStaySchedule = StaySchedule::select('stay_schedules.*')
            ->with(['scheduleDetails', 'scheduleActivities'])
            ->join('semesters', 'stay_schedules.semester_id', '=', 'semesters.id')
            ->where('stay_schedules.employee_id', $person->employee->id)->get()->last();
        $lastStaySchedule->makeHidden(['employee_id', 'created_at', 'updated_at']);
        $lastStaySchedule->scheduleDetails->makeHidden(['id', 'stay_schedule_id', 'created_at', 'updated_at']);
        $lastStaySchedule->scheduleActivities->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at']);
        $candidate = [
            'id'                => $person->id,
            'name'              => $person->first_name . ' ' . $person->middle_name . ' ' . $person->last_name,
            'escalafon_code'    => $person->employee->escalafon->code,
            'escalafon_name'    => $person->employee->escalafon->name,
            'salary'            => $person->employee->escalafon->salary,
            'details'           => $lastStaySchedule
        ];
        return response(['candidate' => $candidate], 200);
    }
}
