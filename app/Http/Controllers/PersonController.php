<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use App\Models\Escalafon;
use App\Models\Faculty;
use App\Models\Person;
use App\Models\PersonValidation;
use App\Models\{PersonChange,CentralAuthority};
use Illuminate\Http\Request;
use App\Http\Traits\{WorklogTrait, ValidationTrait};
use Illuminate\Support\Facades\Auth;
use Luecano\NumeroALetras\NumeroALetras;
use Carbon\Carbon;

class PersonController extends Controller
{
    use WorklogTrait, ValidationTrait;

    public function allCandidates()
    {
       
        $result = Person::all();
        foreach ($result as $rest) {
            $candidate = [
                'id'        => $rest->id,
                'name'      => $rest->first_name." ".$rest->middle_name,
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
            'bank_account_number'   => 'string|max:120',
            'is_employee'           => 'required|boolean',
        ]);

        if($request->input('nationality') == 'El Salvador') {
            $request->validate([
            'city'                  => 'required|string|max:120',
            'department'            => 'required|string|max:120',
            'nup'                   => 'required|string|max:120',
            'isss_number'           => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date|after:today',
            'nit_number'            => 'required|string|max:120',
            ]);
        } else {
            $request->validate(['passport_number' => 'required|string|max:120']);
        }

        if($request->input('is_employee') == true) {
            $employeeFields = $request->validate([
                'journey_type'     => 'required|string' ,
                'faculty_id'       => 'required|integer|gte:1',
                'escalafon_id'     => 'required|integer|gte:1',
                'employee_type_id' => 'required|integer|gte:1',
            ]);
            Escalafon::findOrFail( $employeeFields['escalafon_id']);
            EmployeeType::findOrFail($employeeFields['employee_type_id']);
            Faculty::findOrFail($employeeFields['faculty_id']);
        } 

        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->first();
        if($person) {
            return response(['message' => "El usuario ya ha registrado sus datos personales",], 400);        
        }

        $newPerson = new Person ($request->all());
        $newPerson->user_id = $user->id;
        if($newPerson->nationality == 'El Salvador') {
            $newPerson->dui_text = $this->duiToText($newPerson->dui_number);
            $newPerson->nit_text = $this->nitToText($newPerson->nit_number);
        }
        $newPerson->save();

        PersonChange::create(['person_id'=>$newPerson->id,'change'=>"Se registraron los datos personales generales."]);
        $this->RegisterAction("El usuario he registrado sus datos personales generales", "medium");

        if($request->input('is_employee') == true) {
            $newPerson->employee()->create($employeeFields);
            PersonChange::create(['person_id'=>$newPerson->id,'change'=>"Se registraron los datos del profesor."]);
            $this->RegisterAction("El usuario he registrado como profesor", "medium");
        }

        $personValidation = new PersonValidation(['person_id' => $newPerson->id]);
        $personValidation->save();

        return response(['person' => $newPerson], 201);
    }

    public function show($id)
    {
        $person = Person::where('id',$id)->with('employee',function($query){
            $query->with('faculty')->with('escalafon')->with('employeeType');
        })->firstOrFail();
        return response(['person' => $person,], 200);
    }

    public function showMyInfo()
    {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->with('employee',function($query){
            $query->with('faculty')->with('escalafon')->with('employeeType');
        })->firstOrFail();
        return response($person, 200);
    }

    public function hasRegistered()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->first();
        if($person) {
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
            'bank_account_number'   => 'string|max:120',
            'is_employee'           => 'required|boolean',
        ]);

        if($request->input('nationality') == 'El Salvador') {
            $request->validate([
            'city'                  => 'required|string|max:120',
            'department'            => 'required|string|max:120',
            'nup'                   => 'required|string|max:120',
            'isss_number'           => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date|after:today',
            'nit_number'            => 'required|string|max:120',
            ]);
        } else {
            $request->validate(['passport_number' => 'required|string|max:120']);
        }

        if($request->input('is_employee') == true) {
            $employeeFields = $request->validate([
                'journey_type'     => 'required|string' ,
                'faculty_id'       => 'required|integer|gte:1',
                'escalafon_id'     => 'required|integer|gte:1',
                'employee_type_id' => 'required|integer|gte:1',
            ]);
            Escalafon::findOrFail( $employeeFields['escalafon_id']);
            EmployeeType::findOrFail($employeeFields['employee_type_id']);
            Faculty::findOrFail($employeeFields['faculty_id']);
        }
        
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();

        $person->update($request->all());
        if($person->nationality == 'El Salvador') {
            $person->dui_text = $this->duiToText($person->dui_number);
            $person->nit_text = $this->nitToText($person->nit_number);
        }
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se actualizo la información general de datos personales"]);
        $this->RegisterAction("El usuario ha actualizado sus datos personales generales", "medium");

        if($request->input('is_employee') == true) {
            $employee = $person->employee;
            if($employee == null) {
                $person->employee()->create($employeeFields);
                PersonChange::create(['person_id'=>$person->id,'change'=>"Se registraron los datos del profesor."]);
                $this->RegisterAction("El usuario he registrado como profesor", "medium");
            } else {
                $employee->update($employeeFields);
                PersonChange::create(['person_id'=>$person->id,'change'=>"Se actualizaron los datos del profesor."]);
                $this->RegisterAction("El usuario ha actualizado sus datos de profesor", "medium");
            }
        }

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

    public function storeMenu(Request $request){
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
            default:
                # code...
                break;
        }
        $this->updatePersonStatus($person);
        return response(['person' => $person,], 200);
    }

    public function storeDui(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el DUI"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del DUI", "medium"); 
        return $person;
    }

    public function storePassport(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('pass');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-PASAPORTE.pdf";
        $person->passport = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Pasaporte"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del Pasaporte", "medium"); 
        return  $person;
    }

    public function storeNit(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el NIT"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del NIT", "medium"); 
        return $person;
    }

    public function storeBank(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('banco');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-CuentaDeBanco.pdf";
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene la Cuenta Bancaria"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su cuenta de banco", "medium"); 
        return $person;

    }

    public function storeTitle(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('titulo');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Titulo.pdf";
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Titulo Univesitario"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su titulo Universitario", "medium"); 
        return  $person;
    }

    public function storeCurriculum(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('cv');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Curriculum.pdf";
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su curriculum", "medium"); 
        return  $person;
    }

    public function storePermission(Request $request)
    {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('permiso');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-permission.pdf";
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Permiso de laborar en la facultad"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo"); 
        return $person;
    }

    public function updateMenu(Request $request){
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
                $person =  $this->storeTitle($request);
                break;

            case 'permiso':
                $person =  $this->storePermission($request);
                break;
            case 'pass':
                $person =  $this->storePassport($request);
                break;
            default:
                # code...
                break;
        }
        $this->updatePersonStatus($person);
        return response(['person' => $person,], 200);
    }

    public function updateDui(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->dui);
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el DUI"]);
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

    public function updateNit(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->nit);
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el NIT"]);
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

    public function updateBank(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('banco');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-CuentaDeBanco.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->bank_account);
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene la cuenta bancaria"]);
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

    public function updateTitle(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('titulo');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Titulo.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->professional_title_scan);
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el Titulo Universitario"]);
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

    public function updateCurriculum(Request $request){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('cv');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-curriculum.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->curriculum);
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $personValidations = $person->personValidations;
        $personValidations->update([
            'curriculum_readable'      => false,
            'curriculum'      => false,
        ]); 
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su curriculum", "medium"); 
        return $person;
    }

    public function updatePermisssion(Request $request) {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('permiso');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-permission.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->work_permission);
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el permiso de trabajo"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $personValidations = $person->personValidations;
        $personValidations->update([
            'work_permission_readable'      =>  false,
            'work'      =>  false,
        ]); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo"); 
        return  $person;
    }

    public function updatePassport(Request $request) {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $file = $request->file('pass');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-PASAPORTE.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->passport);
        $person->passport = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el Pasaporte"]);
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

    public function getMenu($type){
        
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
            default:
                # code...
                break;
        }
        return response(['person' => $person], 200);
    }


    public function getDui(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
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
        return ['pdfDui' => $pdf,
                'validations'=> $validations ];
    }

    public function getPassport(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $validations = [
            'passport_readable'      =>  $person->personValidations->passport_readable,
            'passport_name'          =>  $person->personValidations->passport_name,
            'passport_number'        =>  $person->personValidations->passport_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->passport));
        return ['pdfPassport' => $pdf,
        'validations'=> $validations ];
    }

    public function getNit(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $validations = [
            'nit_readable'      =>  $person->personValidations->nit_readable,
            'nit_name'          =>  $person->personValidations->nit_name,
            'nit_number'        =>  $person->personValidations->nit_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->nit));
        return ['pdfNit' => $pdf,
        'validations'=> $validations];
    }

    public function getBank(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $validations = [
            'bank_readable'      =>  $person->personValidations->bank_readable,
            'bank_number'          =>  $person->personValidations->bank_number,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->bank_account));
        return ['pdfBank' => $pdf,
        'validations'=> $validations];
    }

    public function getTitle(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
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
        return ['pdfTitle' => $pdf,
        'validations'=> $validations];
    }

    public function getCurriculum(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $validations = [
            'curriculum_readable'      =>  $person->personValidations->curriculum_readable,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->curriculum));
        return ['pdfCurriculum' => $pdf,
        'validations'=> $validations];
    }

    public function getPermission() {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $validations = [
            'work_permission_readable'      =>  $person->personValidations->work_permission_readable,
        ];
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->work_permission));
        return ['pdfPermission' => $pdf,
        'validations'=> $validations];
    }

    public function duiToText($dui){
        $formatter = new NumeroALetras();
        if (substr($dui,0,-9) == 0 && substr($dui,1,-8) == !0) {
          return  $textDui = "CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        } if(substr($dui,1,-8) == 0 && substr($dui,2,-7) == !0) {
            return $textDui = "CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }if(substr($dui,2,-7) == 0 && substr($dui,3,-6) == !0){
            return $textDui = "CERO CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }else{
           return $textDui = "CERO CERO CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }
    }

    public function nitToText($nit){
        $formatter = new NumeroALetras();
        $nitParts = explode("-",$nit);
        
         if (substr($nitParts[0],0,-3) == 0 && substr($nitParts[0],1,-2) == !0) {
            $part1 ="CERO ".$formatter->toString($nitParts[0])."";
        } else {
            $part1 ="CERO CERO ".$formatter->toString($nitParts[0])."";
        }
        
        if (substr($nitParts[1],0,-5) == 0 && substr($nitParts[1],1,-4) == !0) {
             $part2 ="CERO ".$formatter->toString($nitParts[1])."";
        } if(substr($nitParts[1],1,-4) == 0){
             $part2 ="CERO CERO ".$formatter->toString($nitParts[1])."";
        }else{
             $part2 = $formatter->toString($nitParts[1]);
        }

        if (substr($nitParts[2],0,-2) == 0 && substr($nitParts[2],1,-1) == !0) {
             $part3 ="CERO ".$formatter->toString($nitParts[2])."";
        } if(substr($nitParts[2],1,-1) == 0){
             $part3 ="CERO CERO ".$formatter->toString($nitParts[2])."";
        }else{
             $part3 = $formatter->toString($nitParts[2]);
        }
        $part4 =  $formatter->toString($nitParts[3]);
        return $textNIt = "".$part1."-".$part2."-".$part3."-".$part4."";
        
    }

    public function myChanges()
    {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $changes = PersonChange::where('person_id',$person->id)->get();
        return response(['changes' => $changes,], 200);
    }

    public function getDocumentsByCase()
    {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        

        if ($person->employee == null) {
            //Si no es empleado verificamos que sea nacional o extanjero
            if ($person->nationality == 'El Salvador') {
                return  response(['archivos' => ['dui','nit','banco','cv','titulo']], 200);
            } else {
                //EXTRANJERO
                return  response(['archivos' =>  ['banco','cv','titulo','pass']], 200);
            }
        } else {
            //Candidato - Trabajador
            if ($person->nationality == 'El Salvador') {
                //Candidato - Trabajador - Nacional
                if ($person->employee->faculty_id == 1) {
                    return  response(['archivos' =>  ['dui','nit','banco','cv','titulo']], 200);
                } else {
                    return  response(['archivos' =>  ['dui','nit','banco','cv','titulo','permiso']], 200);
                    
                }
            } else {
                //Candidato - Trabajador - Internacional 
                if ($person->employee->faculty_id == 1) {
                    return  response(['archivos' =>  ['banco','cv','titulo','pass']], 200);
                } else {
                    return  response(['archivos' =>  ['banco','cv','titulo','permiso','pass']], 200);
                }
            }
        }
    }
    public function calculaedad($fechanacimiento){
        list($ano,$mes,$dia) = explode("-",$fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
          $ano_diferencia--;
        return $ano_diferencia;
      }

    public function wordExample($id){
     $rector = CentralAuthority::where('position','=','Rector')->get()->first();
     
     //Preparando los datos del rector
     $formatter = new NumeroALetras();
     $edad = $this->calculaedad($rector->birth_date);
     $numeroAcuerdo = 'FIA-666-2021';
     $nombreRector = "".$rector->firstName." ".$rector->middleName." ".$rector->lastName."";
     $firmaRector = $rector->reading_signature;
     $edadRector = $formatter->toString($edad);
     $profesionRector = $rector->profession;
     $duiTextoRector = $rector->text_dui;
     $nitTextoRector = $rector->text_nit;
     
     //Preparando la Información del candidato
     $candidato = Person::findOrFail($id);
     $edadC = $this->calculaedad($candidato->birth_date);
     $nombreCandidato = "".$candidato->first_name." ".$candidato->middle_name." ".$candidato->last_name."";
     $candidatoEdad = $formatter->toString($edadC);
     $candidatoProfesion = $candidato->professional_title;
     $candidatoCiudad = $candidato->city;
     $candidatoDepartamento = $candidato->department;
     $duiTextoCandidato = $candidato->dui_text;
     $nitTextoCandidato = $candidato->nit_text;
     $date = Carbon::now()->locale('es');
     $fecha = "A LOS  ".$formatter->toString($date->day)." DIAS DEL MES DE  ".$date->monthName." DE ".$formatter->toString($date->year)."";
     try {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor(\Storage::disk('formats')->path('/SPNP.docx'));
        $phpWord->setValue('numeroAcuerdo',$numeroAcuerdo);
        $phpWord->setValue('nombreRector',strtoupper($nombreRector));
        $phpWord->setValue('firmaRector',$firmaRector);
        $phpWord->setValue('edadRector',strtoupper($edadRector));
        $phpWord->setValue('duiTextoRector',strtoupper($duiTextoRector));
        $phpWord->setValue('nitTextoRector',strtoupper($nitTextoRector));
        $phpWord->setValue('profesionRector',strtoupper($profesionRector));
        $phpWord->setValue('nombreCandidato',strtoupper($nombreCandidato));

        $phpWord->setValue('candidatoEdad',strtoupper($candidatoEdad));
        $phpWord->setValue('candidatoProfesion',strtoupper($candidatoProfesion));
        $phpWord->setValue('candidatoCiudad',strtoupper($candidatoCiudad));
        $phpWord->setValue('candidatoDepartamento',strtoupper($candidatoDepartamento));
        $phpWord->setValue('candidatoDui',strtoupper($duiTextoCandidato));
        $phpWord->setValue('candidatoNit',strtoupper($nitTextoCandidato));
        $phpWord->setValue('fecha',strtoupper($fecha));
       
        $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');
        $phpWord->saveAs($tenpFile);
        
        $header = [
            "Content-Type: application/octet-stream",
      ]; 
        return response()->download($tenpFile, 'Contrato Generado Servicios Profesionales.docx', $header)->deleteFileAfterSend($shouldDelete = true);
    } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
        //throw $th;
        return back($e->getCode());
    }

    }
}
