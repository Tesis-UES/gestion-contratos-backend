<?php

namespace App\Http\Controllers;

use App\Models\{Person};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ValidationDocsNotification;
use App\Http\Traits\{WorklogTrait, ValidationTrait};
use Mail;


class PersonValidationController extends Controller
{
    use WorklogTrait, ValidationTrait;
    /*Funcion que retornara las validaciones a realizar segun el tipo de candiato */
    
    public function myValidationStatus(){
        $user = Auth::user();
        $person = Person::where('user_id',$user->id)->firstOrFail();
        $response = $this->getValidations($person->id);
        return $response;
    }
    
    public function getValidations($id)
    {
        $person = Person::findOrFail($id);
        $personal = [
            'id'        => $person->id,
            'nombre'    => $person->first_name . " " . $person->middle_name . " " . $person->last_name,
        ];

        if ($person->employee == null) {
            //Si no es empleado verificamos que sea nacional o extanjero
            if ($person->nationality == 'El Salvador') {
                return  response(['Menu' =>  $this->validateNational($person), 'personal' => $personal], 200);
            } else {
                //EXTRANJERO
                return  response(['Menu' =>  $this->validateInternational($person), 'personal' => $personal], 200);
            }
        } else {
            //Candidato - Trabajador
            if ($person->nationality == 'El Salvador') {
                //Candidato - Trabajador - Nacional
                if ($person->employee->faculty_id == 1) {
                    return  response(['Menu' =>  $this->validateNational($person), 'personal' => $personal], 200);
                } else {

                    $menu =  $this->validateNational($person);
                    $var = [
                        'Nombre' => 'Permiso de Trabajo',
                        'Estado' => '',
                        'codigo' => 'PERMISO'
                    ];

                    if ($person->personValidations->work_permission_readable) {
                        $var['Estado'] = 'Validado';
                        array_push($menu, $var);
                    } else {
                        $var['Estado'] = 'Pendiente';
                        array_push($menu, $var);
                    }
                    return response(['Menu' =>  $menu, 'personal' => $personal], 200);
                }
            } else {
                //Candidato - Trabajador - Internacional 
                if ($person->employee->faculty_id == 1) {
                    return  response(['Menu' =>  $this->validateInternational($person), 'personal' => $personal], 200);
                } else {
                    $menu =  $this->validateInternational($person);
                    $var = [
                        'Nombre' => 'Permiso de Trabajo',
                        'Estado' => '',
                        'codigo' => 'PERMISO'
                    ];
                    if ($person->personValidations->work_permission_readable) {
                        $var['Estado'] = 'Validado';
                        array_push($menu, $var);
                    } else {
                        $var['Estado'] = 'Pendiente';
                        array_push($menu, $var);
                    }
                    return response(['Menu' =>  $menu, 'personal' => $personal], 200);
                }
            }
        }
    }

    public function validateNational(Person $person)
    {
        $menu = array();
        $var = [
            'Nombre' => 'Documento Unico De Identidad DUI',
            'Estado' => '',
            'codigo' => 'DUI'
        ];
        if ($person->personValidations->dui_readable && $person->personValidations->dui_name && $person->personValidations->dui_number && $person->personValidations->dui_profession && $person->personValidations->dui_civil_status && $person->personValidations->dui_birth_date && $person->personValidations->dui_unexpired && $person->personValidations->dui_address) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        //VERIFICAMOS NIT
        $var = [
            'Nombre' => 'Numero de Identificación Tributaria NIT',
            'Estado' => '',
            'codigo' => 'NIT'
        ];
        if ($person->personValidations->nit_readable && $person->personValidations->nit_name && $person->personValidations->nit_number) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        //VERIFICAMOS Cuenta de Banco
        $var = [
            'Nombre' => 'Cuenta de Banco',
            'Estado' => '',
            'codigo' => 'BANCO'
        ];
        if ($person->personValidations->bank_readable && $person->personValidations->bank_number) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        $var = [
            'Nombre' => 'Curriculum',
            'Estado' => '',
            'codigo' => 'CV'
        ];
        if ($person->personValidations->curriculum_readable) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        $var = [
            'Nombre' => 'Titulo',
            'Estado' => '',
            'codigo' => 'TITULO-N'
        ];
        if ($person->personValidations->title_readable && $person->personValidations->title_mined) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        return $menu;
    }

    public function validateInternational(Person $person)
    {

        $menu = array();
        $var = [
            'Nombre' => 'Pasaporte',
            'Estado' => '',
            'codigo' => 'PASS'
        ];
        if ($person->personValidations->passport_readable && $person->personValidations->passport_name && $person->personValidations->passport_number) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }

        //VERIFICAMOS Cuenta de Banco
        $var = [
            'Nombre' => 'Cuenta de Banco',
            'Estado' => '',
            'codigo' => 'BANCO'
        ];
        if ($person->personValidations->bank_readable && $person->personValidations->bank_number) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        $var = [
            'Nombre' => 'Curriculum',
            'Estado' => '',
            'codigo' => 'CV'
        ];
        if ($person->personValidations->curriculum_readable) {

            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        $var = [
            'Nombre' => 'Titulo',
            'Estado' => '',
            'codigo' => 'TITULO-I'
        ];
        if ($person->personValidations->title_readable && $person->personValidations->title_apostilled && $person->personValidations->title_apostilled_readable && $person->personValidations->title_authentic) {
            $var['Estado'] = 'Validado';
            array_push($menu, $var);
        } else {
            $var['Estado'] = 'Pendiente';
            array_push($menu, $var);
        }
        return  $menu;
    }

    public function validationData(Person $person, $type)
    {

        switch ($type) {
            case 'DUI':
                return $this->getDuiValidation($person);
                break;

            case 'NIT':
                return $this->getNitValidation($person);
                break;

            case 'BANCO':
                return $this->getBankValidation($person);
                break;

            case 'CV':
                return $this->getCVValidation($person);
                break;

            case 'TITULO-N':
                return $this->getTNValidation($person);
                break;

            case 'TITULO-I':
                return $this->getTIValidation($person);
                break;

            case 'PERMISO':
                return $this->getWPValidation($person);
                break;
            case 'PASS':
                return $this->getPASSValidation($person);
                break;
            default:
                # code...
                break;
        }
    }

    public function getDuiValidation(Person $person)
    {

        $data = [
            'nombre'            => $person->first_name . " " . $person->middle_name . " " . $person->last_name,
            'direccion'         => $person->address,
            'municipio'         => $person->city,
            'departamento'      => $person->department,
            'profesion'         => $person->professional_title,
            'fecha_nacimiento'   => $person->birth_date,
            'estado_civil'      => $person->civil_status,
            'dui'               => $person->dui_number,
            'fecha_vencimiento'  => $person->dui_expiration_date,
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->dui)),
        ];
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
        return ['data' => $data, 'validations' => $validations];
    }

    public function getNitValidation(Person $person)
    {

        $data = [
            'nombre'            => $person->first_name . " " . $person->middle_name . " " . $person->last_name,
            'numero_nit'         => $person->nit_number,
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->nit)),
        ];
        $validations = [
            'nit_readable'      =>  $person->personValidations->nit_readable,
            'nit_name'          =>  $person->personValidations->nit_name,
            'nit_number'        =>  $person->personValidations->nit_number,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getBankValidation(Person $person)
    {

        $data = [
            'numero_banco'         => $person->bank_account_number,
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->bank_account)),
        ];
        $validations = [
            'bank_readable'      =>  $person->personValidations->bank_readable,
            'bank_number'          =>  $person->personValidations->bank_number,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getCVValidation(Person $person)
    {

        $data = [
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->curriculum)),
        ];
        $validations = [
            'curriculum_readable'      =>  $person->personValidations->curriculum_readable,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getWPValidation(Person $person)
    {

        $data = [
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->work_permission)),
        ];
        $validations = [
            'work_permission_readable'      =>  $person->personValidations->work_permission_readable,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getPASSValidation(Person $person)
    {

        $data = [
            'nombre'            => $person->first_name . " " . $person->middle_name . " " . $person->last_name,
            'numero_pasaporte'  => $person->passport_number,
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->passport)),
        ];
        $validations = [
            'passport_readable'      =>  $person->personValidations->passport_readable,
            'passport_name'          =>  $person->personValidations->passport_name,
            'passport_number'        =>  $person->personValidations->passport_number,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getTNValidation(Person $person)
    {

        $data = [
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->professional_title_scan)),
        ];
        $validations = [
            'title_readable'      =>  $person->personValidations->title_readable,
            'title_mined'      =>  $person->personValidations->title_mined,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function getTIValidation(Person $person)
    {

        $data = [
            'pdf'               =>  base64_encode(\Storage::disk('personalFiles')->get($person->professional_title_scan)),
        ];
        $validations = [
            'title_readable'      =>  $person->personValidations->title_readable,
            'title_apostilled'      =>  $person->personValidations->title_apostilled,
            'title_apostilled_readable'      =>  $person->personValidations->title_apostilled_readable,
            'title_authentic'      =>  $person->personValidations->title_authentic,
        ];
        return ['data' => $data, 'validations' => $validations];
    }

    public function validationStore(Person $person, $type, Request $request)
    {
       
        $person->personValidations->update($request['validations']);
        $validado = $this->checkValidationsMail($request['validations']);
        switch ($type) {
            //Envio de correos segun valiadiciones
            case 'DUI':
               if ($validado) {
                $mensaje = "Se ha validado con exito el documento DUI, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
               }else{
                $mensaje = "Se ha validado el documento DUI que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
               } 
                break;

            case 'NIT':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento NIT, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento NIT que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;

            case 'BANCO':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento de su comprobante de cuenta bancaria, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento de su comprobante de cuenta bancaria, que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;

            case 'CV':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento que contiene el Curriculum Vitae, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento que contiene el Curriculum Vitae que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;

            case 'TITULO-N':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento que contiene sus Titulos Profesionales, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento que contiene sus Titulos Profesionales que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;

            case 'TITULO-I':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento que contiene sus Titulos Profesionales, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento que contiene sus Titulos Profesionales que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;

            case 'PERMISO':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento que contiene su permiso de trabajo de otra facultad, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento que contiene su permiso de trabajo de otra facultadque ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones  y solventarlas lo mas pronto posible";
                   } 
                break;
            case 'PASS':
                if ($validado) {
                    $mensaje = "Se ha validado con exito el documento que contiene su Pasaporte, cumple con todos los requerimientos, para mas detalles ingresar al sistema con sus credenciales";
                   }else{
                    $mensaje = "Se ha validado el documento que contiene su Pasaporte que ha subido al sistema, pero este no cumple con los requerimientos, por favor verificar entrando en el sistema las Respectivas observaciones y solventarlas lo mas pronto posible";
                   } 
                break;
            default:
                # code...
                break;
        }
        $this->updatePersonStatus($person);
        try {
            Mail::to($person->user->email)->send(new ValidationDocsNotification($mensaje));
            $response = ['mensaje'   =>"Si se envio el correo electronico"];
            return response($response, 201);
        } catch (\Swift_TransportException $e) {
            $response = ['mensaje'   =>"No se ha enviado el correo electronico"];
            return response($response, 201);
         } 
         
    }

    public function checkValidationsMail($validations){
        $control = count($validations);
        $validados = 0;
        foreach ($validations as $key => $value) {
            if ($value == true) {
                $validados++;
            }
        }
        return ($validados == $control) ?  true : false ;
       
    }


}
