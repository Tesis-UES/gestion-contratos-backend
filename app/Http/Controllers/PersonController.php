<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonValidation;
use App\Models\PersonChange;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
use Illuminate\Support\Facades\Auth;
use Luecano\NumeroALetras\NumeroALetras;

class PersonController extends Controller
{
    use WorklogTrait;

    public function allCandidates()
    {
        $result = Person::all();
        foreach ($result as $rest) {
            $candidate = [
                'id'        => $rest->id,
                'name'      => $rest->first_name." ".$rest->middle_name,
                'last_name' => $rest->last_name,
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
            'birth_date'            => 'required|date',
            'gender'                => 'required|string|max:120',
            'telephone'             => 'required|string|max:120',
            'alternate_telephone'   => 'string|max:120',
            'alternate_mail'        => 'string|max:120',
            'address'               => 'required|string|max:120',
            'city'                  => 'required|string|max:120',
            'department'            => 'required|string|max:120',
            'nationality'           => 'required|string|max:120',
            'professional_title'    => 'required|string|max:120',
            'nup'                   =>'required|string|max:120',
            'isss_number'           =>'required|string|max:120',
            'passport_number'       =>'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date',
            'nit_number'            => 'required|string|max:120',
            'bank_account_number'   => 'required|string|max:120',
        ]);

        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->first();
        if($person){
            return response(['message' => "El usuario ya ha registrado sus datos personales",], 400);        
        }
        $newPerson = new Person ($request->all());
        $newPerson->user_id = $user->id;
        $newPerson->dui_text = $this->duiToText($newPerson->dui_number);
        $newPerson->nit_text = $this->nitToText($newPerson->nit_number);
        $newPerson->save();
        PersonChange::create(['person_id'=>$newPerson->id,'change'=>"Se registraron los datos personales generales."]);
        $personValidation = new PersonValidation(['person_id' => $newPerson->id]);
        $personValidation->save();
        
       
        $this->RegisterAction("El usuario he registrado sus datos personales generales", "medium");
        return response([
            'person' => $newPerson,
        ], 201);
    }

    public function show($id)
    {
        $person = Person::findOrFail($id);
        return response(['person' => $person,], 200);
    }

    public function showMyInfo()
    {
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
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
        $user = Auth::user();
        $request->validate([
            'first_name'            => 'required|string|max:120',
            'middle_name'           => 'required|string|max:120',
            'last_name'             => 'required|string|max:120',
            'civil_status'          => 'required|string|max:120',
            'know_as'               => 'string|max:120',
            'married_name'          => 'string|max:120',
            'birth_date'            => 'required|date',
            'gender'                => 'required|string|max:120',
            'telephone'             => 'required|string|max:120',
            'address'               => 'required|string|max:120',
            'alternate_telephone'   => 'string|max:120',
            'alternate_mail'        => 'string|max:120',
            'professional_title'    => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date',
            'nit_number'            => 'required|string|max:120',
            'bank_account_number'   => 'required|string|max:120',
            'nup'                   =>'required|string|max:120',
            'isss_number'           =>'required|string|max:120',
            'passport_number'       =>'required|string|max:120',
        ]);

        $person = Person::where('user_id', $user->id)->firstOrFail();
        $person->update($request->all());
        $person->dui_text = $this->duiToText($person->dui_number);
        $person->nit_text = $this->nitToText($person->nit_number);
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se actualizo la información general de datos personales"]);
                                             
        $this->RegisterAction("El usuario ha actualizado sus datos personales genrales", "medium");
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

    public function storeDui(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el DUI"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del DUI", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storePassport(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('passport');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-PASAPORTE.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Pasaporte"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del Pasaporte", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storeNit(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el NIT"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del NIT", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storeBank(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('account');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-CuentaDeBanco.pdf";
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene la Cuenta Bancaria"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su cuenta de banco", "medium"); 
        return response(['person' => $person,], 200);

    }

    public function storeTitle(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('title');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Titulo.pdf";
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Titulo Univesitario"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su titulo Universitario", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storeCurriculum(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('curriculum');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Curriculum.pdf";
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su curriculum", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storePermission(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $file = $request->file('work_permission');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-permission.pdf";
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se subio y guardo el archivo que contiene el Permiso de laborar en la facultad"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo"); 
        return response(['person' => $person,], 200);
    }

    public function updateDui(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->dui);
        $person->dui = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el DUI"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
       /*  $personValidations = $person->personValidations;
        $personValidations->update([
            'dui_readable'      => false,
            'name_correct'      => false,
            'address_correct'   => false,
            'dui_current'       => false,
        ]); */
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del DUI", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function updateNit(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->nit);
        $person->nit = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el NIT"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        /* $personValidations = $person->personValidations;
        $personValidations->update([
            'nit_readable' => false,
        ]); */
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del NIT", "medium");
        return response(['person' => $person,], 200);
    }

    public function updateBank(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('account');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-CuentaDeBanco.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->bank_account);
        $person->bank_account = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene la cuenta bancaria"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
       /*  $personValidations = $person->personValidations;
        $personValidations->update([
            'bank_account_readable' => false,
        ]); */
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su cuenta de banco", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function updateTitle(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('title');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Titulo.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->professional_title_scan);
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el Titulo Universitario"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        /* $personValidations = $person->personValidations;
        $personValidations->update([
            'profesional_title_readable' => false,
            'profesional_title_validated' => false,
        ]); */
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su titulo Universitario", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function updateCurriculum(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('curriculum');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-curriculum.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->curriculum);
        $person->curriculum = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el curriculum"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        /* $personValidations = $person->personValidations;
        $personValidations->update([
            'curriculum_readable'  => false,
        ]); */
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su curriculum", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function updatePermisssion(Request $request, $id) {
        $person = Person::findOrFail($id);
        $file = $request->file('work_permission');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-permission.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->work_permission);
        $person->work_permission = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el permiso de trabajo"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        /* $personValidations = $person->personValidations;
        $personValidations->update([
            'work_permission_readable'  => false,
        ]); */
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su permiso de trabajo"); 
        return response(['person' => $person,], 200);
    }

    public function updatePassport(Request $request, $id) {
        $person = Person::findOrFail($id);
        $file = $request->file('passport');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-PASAPORTE.pdf";
        //Se elimina el archivo antiguo
        \File::delete($person->passport);
        $person->passport = $nombre_archivo;
        $person->save();
        PersonChange::create(['person_id'=>$person->id,'change'=>"Se Actualizo el archivo que contiene el Pasaporte"]);
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha Actualizado el  archivo pdf que contiene su Pasaporte"); 
        return response(['person' => $person,], 200);
    }

    public function getDui($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->dui));
        return response(['pdfDui' => $pdf], 200);
    }

    public function getPassport($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->passport));
        return response(['pdfPassport' => $pdf], 200);
    }

    public function getNit($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->nit));
        return response(['pdfNit' => $pdf], 200);
    }

    public function getBank($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->bank_account));
        return response(['pdfBank' => $pdf], 200);
    }

    public function getTitle($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->professional_title_scan));
        return response(['pdfTitle' => $pdf], 200);
    }

    public function getCurriculum($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->curriculum));
        return response(['pdfCurriculum' => $pdf], 200);
    }

    public function getPermission($id) {
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->work_permission));
        return response(['pdfPermission' => $pdf], 200);
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
}
