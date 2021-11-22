<?php

namespace App\Http\Controllers;

use App\Models\Escalafon;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
use App\Mail\ValidationDocsNotification;
use App\Models\User;
use Mail;

class EscalafonController extends Controller
{
    use WorklogTrait;
    public function all()
    {
        $escalafons = Escalafon::all();
        $this->RegisterAction("El usuario ha consultado el catalogo de Escalafones");
        return response($escalafons, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function getAdminMail(){
        $admin = User::all();
        $emails = [];
       foreach ($admin as $admins) {
            if ($admins->roles[0]->name == 'Administrador') {
               $email[] = $admins->email; 
            }

        }
        return $email;
     }


    public function store(Request $request)
    {
        
        $fields = $request->validate([
            'code'   => 'required|string|max:10',
            'name'   => 'required|string|max:120',
            'salary' => 'required|integer',
        ]);

        $newEscalafon = Escalafon::create([
            'code'   => $fields['code'],
            'name'   => $fields['name'],
            'salary' => $fields['salary'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de Escalafones", "high");
        $email = $this->getAdminMail();
        $mensajeEmail = "Se ha registrado un nuevo escalafón con el nombre <b>".$fields['name']."</b> con el código de Identificación <b>".$fields['code']."</b> Con un monto de Salario <b>$".number_format($fields['salary'],2).".</b>";
        
        foreach ($email as $emails) {
            try {
                Mail::to($emails)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            } 
        }
       
         return response([
            'escalafon' => $newEscalafon,
            'mensaje'   => $mensaje,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $escalafon = Escalafon::findOrFail($id);
        return response(['escalafon' => $escalafon,], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code'   => 'required|string|max:10',
            'name'   => 'required|string|max:120',
            'salary' => 'required|integer',
        ]);

        $escalafon = Escalafon::findOrFail($id);
        $codigoAnterior = $escalafon->code;
        $nombreAnterior = $escalafon->name;
        $salarioAnterior = $escalafon->salary;
        $escalafon->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del escalafon ".$nombreAnterior." en el catalogo de escalafones", "high");
        $email = $this->getAdminMail();
        $mensajeEmail = "Se ha actualizado el escalafón con el nombre <b>".$nombreAnterior."</b> con los siguientes datos:<br>
        <b>Datos Antiguos:</b>
        <ul>
            <li>Código: <b>".$codigoAnterior."</b> </li>
            <li>Nombre Escalafón: <b>".$nombreAnterior."</b> </li>
            <li>Salario: <b>$".number_format($salarioAnterior,2)."</b> </li>
        </ul><br>
        <b>Datos Nuevos:</b>
        <ul>
            <li>Código: <b>".$escalafon->code."</b> </li>
            <li>Nombre Escalafón: <b>".$escalafon->name."</b> </li>
            <li>Salario: <b>$".number_format($escalafon->salary,2)."</b> </li>
        </ul>
        ";
        
        foreach ($email as $emails) {
            try {
                Mail::to($emails)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            } 
        }
       
         return response([
            'escalafon' => $escalafon,
            'mensaje'   => $mensaje,
        ], 201);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Escalafon  $escalafon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $escalafon = Escalafon::findOrFail($id);
        $codigo = $escalafon->code;
        $nombre = $escalafon->name;
        $salario = $escalafon->salary;
        $escalafon->delete();
        $this->RegisterAction("El usuario ha eliminado el registro del escalafon ".$escalafon->name." en el catalogo de Escalafones" , "high");
        $email = $this->getAdminMail();
        $mensajeEmail = "Se ha eliminado el escalafón con el nombre <b>".$escalafon->name."</b> con los siguientes datos:<br>
        <b>Datos Del escalafón eliminado:</b>
        <ul>
            <li>Código: <b>".$codigo."</b> </li>
            <li>Nombre Escalafón: <b>".$nombre."</b> </li>
            <li>Salario: <b>$".number_format($salario,2)."</b> </li>
        </ul><br>
     ";
        
        foreach ($email as $emails) {
            try {
                Mail::to($emails)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            } 
        }
        return response(null, 204);
    }
}
