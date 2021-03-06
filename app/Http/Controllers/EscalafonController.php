<?php

namespace App\Http\Controllers;

use App\Models\Escalafon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Traits\WorklogTrait;
use App\Mail\ValidationDocsNotification;
use App\Models\User;
use Spatie\Permission\Models\Role;
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
        $role = Role::where('name','Administrador')->first();
        $admin = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->get()->toArray();
        return $admin;
     }

     public function getDecanoMail(){
        $role = Role::where('name','Decano')->first();
        $admin = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->get()->toArray();
        return $admin;
     }

     public function getFinancieroMail(){
        $role = Role::where('name','Asistente Financiero')->first();
        $admin = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->select('users.email')->where('model_has_roles.role_id', '=', $role->id)->get()->toArray();
        return $admin;
     }

    public function store(Request $request)
    {
        
        $fields = $request->validate([
            'code'   => 'required|string|max:10',
            'name'   => 'required|string|max:120',
            'salary' => 'required|numeric',
            'hour_price' => 'required|numeric',	
        ]);

        $newEscalafon = Escalafon::create([
            'code'   => $fields['code'],
            'name'   => $fields['name'],
            'salary' => $fields['salary'],
            'hour_price' => $fields['hour_price'],
        ]);
        $this->RegisterAction("El usuario ha Ingresado un nuevo registro en el catalogo de Escalafones", "high");
        $emails = $this->getAdminMail();
        $mensajeEmail = "Se ha registrado un nuevo escalaf??n con el nombre <b>".$fields['name']."</b> con el c??digo de Identificaci??n <b>".$fields['code']."</b> Con un monto de Salario <b>$".number_format($fields['salary'],2).".</b>";
        
        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
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
            'salary' => 'required|numeric',
            'hour_price' => 'required|numeric',
        ]);

        $escalafon = Escalafon::findOrFail($id);
        $codigoAnterior = $escalafon->code;
        $nombreAnterior = $escalafon->name;
        $salarioAnterior = $escalafon->salary;
        $valorHoraAnterior = $escalafon->hour_price;
        $escalafon->update($request->all());
        $this->RegisterAction("El usuario ha actualizado el registro del escalafon ".$nombreAnterior." en el catalogo de escalafones", "high");
        $emails = $this->getAdminMail();
        $emailDecano = $this->getDecanoMail();
        $emailFinanciero = $this->getFinancieroMail();
        $mensajeEmail = "Se ha actualizado el escalaf??n con el nombre <b>".$nombreAnterior."</b> con los siguientes datos:<br>
        <b>Datos Antiguos:</b>
        <ul>
            <li>C??digo: <b>".$codigoAnterior."</b> </li>
            <li>Nombre Escalaf??n: <b>".$nombreAnterior."</b> </li>
            <li>Salario: <b>$".number_format($salarioAnterior,2)."</b> </li>
            <li>Valor Hora: <b>$".number_format($valorHoraAnterior,2)."</b> </li>
        </ul><br>
        <b>Datos Nuevos:</b>
        <ul>
            <li>C??digo: <b>".$escalafon->code."</b> </li>
            <li>Nombre Escalaf??n: <b>".$escalafon->name."</b> </li>
            <li>Salario: <b>$".number_format($escalafon->salary,2)."</b> </li>
            <li>Valor Hora: <b>$".number_format($escalafon->hour_price,2)."</b> </li>
        </ul>
        ";
        
        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            } 
        }
        
        foreach ($emailDecano as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                $mensaje = 'Se envio el correo con exito';
            } catch (\Swift_TransportException $e) {
                $mensaje = 'No se envio el correo';
            }
        }

        foreach ($emailFinanciero as $email) {
            try {
                Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
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
        $empleado = Employee::where('escalafon_id',$escalafon->id)->get();
        if($empleado->count() > 0){
            return response([
                'message' => 'No se puede eliminar el escalaf??n ya que tiene empleados asignados',
            ], 422);
        }else{
            $codigo = $escalafon->code;
            $nombre = $escalafon->name;
            $salario = $escalafon->salary;
            $escalafon->delete();
            $this->RegisterAction("El usuario ha eliminado el registro del escalafon ".$escalafon->name." en el catalogo de Escalafones" , "high");
            $emails = $this->getAdminMail();
            $mensajeEmail = "Se ha eliminado el escalaf??n con el nombre <b>".$escalafon->name."</b> con los siguientes datos:<br>
            <b>Datos Del escalaf??n eliminado:</b>
            <ul>
                <li>C??digo: <b>".$codigo."</b> </li>
                <li>Nombre Escalaf??n: <b>".$nombre."</b> </li>
                <li>Salario: <b>$".number_format($salario,2)."</b> </li>
            </ul><br>
         ";
            
            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new ValidationDocsNotification($mensajeEmail,'escalafones'));
                    $mensaje = 'Se envio el correo con exito';
                } catch (\Swift_TransportException $e) {
                    $mensaje = 'No se envio el correo';
                } 
            }
            return response(null, 204);
        }
       
    }
}
