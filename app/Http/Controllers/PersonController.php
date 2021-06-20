<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
use Illuminate\Support\Facades\Auth;


class PersonController extends Controller
{
    use WorklogTrait;

    public function store(Request $request)
    {
        $fields = $request->validate([
            'first_name'    => 'required|string|max:120',
            'middle_name'   => 'required|string|max:120',
            'last_name'     => 'required|string|max:120',
            'civil_status'  => 'required|string|max:120',
            'birth_date'    => 'required|date',
            'gender'        => 'required|string|max:120',
            'telephone'     => 'required|string|max:120',
            'email'         => 'required|string|max:120',
            'address'     => 'required|string|max:120',
            'professional_title'     => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date',
            'nit_number'                   => 'required|string|max:120',
            'bank_account_number'   => 'required|string|max:120',

        ]);


        $usuario = Auth::user();

        $newPerson = Person::create([
            'user_id'                   => $usuario->id,
            'first_name'                => $request['first_name'],
            'middle_name'               => $request['middle_name'],
            'last_name'                 => $request['last_name'],
            'know_as'                   => $request['know_as'],
            'civil_status'              => $request['civil_status'],
            'married_name'              => $request['married_name'],
            'birth_date'                => $request['birth_date'],
            'gender'                    => $request['gender'],
            'telephone'                 => $request['telephone'],
            'email'                     => $request['email'],
            'address'                   => $request['address'],
            'professional_title'        => $request['professional_title'],
            'dui_number'                => $request['dui_number'],
            'dui_expiration_date'       => $request['dui_expiration_date'],
            'nit_number'                => $request['nit_number'],
            'bank_account_number'       => $request['bank_account_number'],
        ]);
        $this->RegisterAction("El usuario he registrado sus datos personales generales");
        return response([
            'person' => $newPerson,
        ], 201);
    }


    public function show($id)
    {
        $person = Person::findOrFail($id);
        return response(['person' => $person,], 200);
    }



    public function update(Request $request, $id)
    {
        $request->validate([]);

        $person = Person::findOrFail($id);
        $person->update($request->all());
        $this->RegisterAction("El usuario ha actualizado sus datos personales genrales");
        return response(['person' => $person], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
        $this->RegisterAction("Se ha eleminado la informacion general del usuario");
        return response(null, 204);
    }

    public function storeDui(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del DUI"); 
        return response(['person' => $person,], 200);
    }

    public function storeNit(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        $person->nit = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del NIT"); 
        return response(['person' => $person,], 200);
    }

    public function storeBank(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('account');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-CuentaDeBanco.pdf";
        $person->bank_account = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su cuenta de banco"); 
        return response(['person' => $person,], 200);

    }

    public function storeTitle(Request $request, $id){

        $person = Person::findOrFail($id);
        $file = $request->file('title');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Titulo.pdf";
        $person->professional_title_scan = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen de su titulo Universitario"); 
        return response(['person' => $person,], 200);
    }

    public function storeCurriculum(Request $request, $id){

        $person = Person::findOrFail($id);
        $file = $request->file('curriculum');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-Curriculum.pdf";
        $person->curriculum = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su curriculum"); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del DUI"); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene la imagen del NIT");
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su cuenta de banco"); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el  archivo pdf que contiene la imagen de su titulo Universitario"); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su curriculum"); 
        return response(['person' => $person,], 200);

    }

    

}
