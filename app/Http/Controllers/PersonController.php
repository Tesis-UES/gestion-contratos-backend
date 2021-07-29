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
        $request->validate([
            'first_name'    => 'required|string|max:120',
            'middle_name'   => 'required|string|max:120',
            'last_name'     => 'required|string|max:120',
            'civil_status'  => 'required|string|max:120',
            'know_as'       => 'string|max:120',
            'married_name'  => 'string|max:120',
            'birth_date'    => 'required|date',
            'gender'        => 'required|string|max:120',
            'telephone'     => 'required|string|max:120',
            'address'       => 'required|string|max:120',
            'professional_title'    => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date',
            'nit_number'            => 'required|string|max:120',
            'bank_account_number'   => 'required|string|max:120',
        ]);

        $usuario = Auth::user();
        $newPerson = new Person ($request->all());
        $newPerson->user_id = $usuario->id;
        $newPerson->save();

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name'    => 'required|string|max:120',
            'middle_name'   => 'required|string|max:120',
            'last_name'     => 'required|string|max:120',
            'civil_status'  => 'required|string|max:120',
            'know_as'       => 'string|max:120',
            'married_name'  => 'string|max:120',
            'birth_date'    => 'required|date',
            'gender'        => 'required|string|max:120',
            'telephone'     => 'required|string|max:120',
            'address'       => 'required|string|max:120',
            'professional_title'    => 'required|string|max:120',
            'dui_number'            => 'required|string|max:120',
            'dui_expiration_date'   => 'required|date',
            'nit_number'            => 'required|string|max:120',
            'bank_account_number'   => 'required|string|max:120',
        ]);

        $person = Person::findOrFail($id);
        $person->update($request->all());
        $this->RegisterAction("El usuario ha actualizado sus datos personales genrales", "medium");
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
        $this->RegisterAction("Se ha eleminado la informacion general del usuario", "medium");
        return response(null, 204);
    }

    public function storeDui(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('dui');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-DUI.pdf";
        $person->dui = $nombre_archivo;
        $person->save();
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file));
        $this->RegisterAction("El usuario ha guardado el archivo pdf que contiene la imagen del DUI", "medium"); 
        return response(['person' => $person,], 200);
    }

    public function storeNit(Request $request, $id){
        $person = Person::findOrFail($id);
        $file = $request->file('nit');
        $nombre_archivo = $person->first_name." ".$person->middle_name." ".$person->last_name."-NIT.pdf";
        $person->nit = $nombre_archivo;
        $person->save();
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha guardado el  archivo pdf que contiene su curriculum", "medium"); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
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
        \Storage::disk('personalFiles')->put($nombre_archivo, \File::get($file)); 
        $this->RegisterAction("El usuario ha actualizado el archivo pdf que contiene su curriculum", "medium"); 
        return response(['person' => $person,], 200);

    }

    public function getDui($id){
        $person = Person::findOrFail($id);
        $pdf = base64_encode(\Storage::disk('personalFiles')->get($person->dui));
        return response(['pdfDui' => $pdf], 200);
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
}
