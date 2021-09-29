<?php

namespace App\Http\Controllers;

use App\Models\{Person};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonValidationController extends Controller
{
    
    /*Funcion que retornara las validaciones a realizar segun el tipo de candiato */
    public function getValidations($id){
        $person = Person::findOrFail( $id);
        //Primero se Verifica si es empleado 
        if ($person->employee == null) {
            //Si no es empleado verificamos que sea nacional o extanjero
            if ($person->nationality == 'El Salvador') {
                //VERIFICAMOS DUI
                if($person->personValidations->dui_readable&&$person->personValidations->dui_name&&$person->personValidations->dui_number&&
                   $person->personValidations->dui_profession&&$person->personValidations->dui_civil_status&&$person->personValidations->dui_birth_date&&
                   $person->personValidations->dui_unexpired&&$person->personValidations->dui_unexpired){
                    dd('todo validado dui');
                }else{
                    dd('pendiente validar dui');
                }
                //VERIFICAMOS NIT
                if($person->personValidations->nit_readable&&$person->personValidations->nit_name&&$person->personValidations->nit_number){
                    dd('todo validado NIT');
                        }else{
                            dd('pendiente validar NIT');
                    }
                //VERIFICAMOS Cuenta de Banco
                if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                    dd('todo validado Banco');
                        }else{
                        dd('pendiente validar Banco');
                    }

                if($person->personValidations->curriculum_readable){
                        dd('todo validado curriculum');
                            }else{
                                dd('pendiente validar curriculum');
                        }
                if($person->personValidations->title_readable&&$person->personValidations->title_mined){
                        dd('todo validado titulo');
                            }else{
                                dd('pendiente validar titulo');
                        }
                

            } else {
                if($person->personValidations->passport_readable&&$person->personValidations->passport_name&&$person->personValidations->passport_number){
                    dd('todo validado pasapporte');
                }else{
                    dd('pendiente validar pasaporte');
                }
                
                //VERIFICAMOS Cuenta de Banco
                if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                    dd('todo validado Banco');
                        }else{
                        dd('pendiente validar Banco');
                    }

                if($person->personValidations->curriculum_readable){
                        dd('todo validado curriculum');
                            }else{
                                dd('pendiente validar curriculum');
                        }
                if($person->personValidations->title_readable&&$person->personValidations->title_apostilled
                   &&$person->personValidations->title_apostilled_redeable&&$person->personValidations->title_authentic){
                        dd('todo validado titulo');
                            }else{
                                dd('pendiente validar titulo');
                        }
                
            }
            
        } else {
            //Candidato - Trabajador
            if ($person->nationality == 'El Salvador') {
                 //Candidato - Trabajador - Nacional
                if ($person->employee->faculty_id == 1) {
                    dd('Empleado Salvadoreño - TRABAJADOR DE LA FIA');
                     //VERIFICAMOS DUI
                if($person->personValidations->dui_readable&&$person->personValidations->dui_name&&$person->personValidations->dui_number&&
                $person->personValidations->dui_profession&&$person->personValidations->dui_civil_status&&$person->personValidations->dui_birth_date&&
                        $person->personValidations->dui_unexpired&&$person->personValidations->dui_unexpired){
                        dd('todo validado dui');
                    }else{
                        dd('pendiente validar dui');
                    }
                    //VERIFICAMOS NIT
                    if($person->personValidations->nit_readable&&$person->personValidations->nit_name&&$person->personValidations->nit_number){
                        dd('todo validado NIT');
                            }else{
                                dd('pendiente validar NIT');
                        }
                    //VERIFICAMOS Cuenta de Banco
                    if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                        dd('todo validado Banco');
                            }else{
                            dd('pendiente validar Banco');
                        }

                    if($person->personValidations->curriculum_readable){
                            dd('todo validado curriculum');
                                }else{
                                    dd('pendiente validar curriculum');
                            }
                    if($person->personValidations->title_readable&&$person->personValidations->title_mined){
                            dd('todo validado titulo');
                                }else{
                                    dd('pendiente validar titulo');
                            }
                } else {
                    dd('Empleado Salvadoreño - TRABAJADOR DE OTRA FACULTAD');
                         //VERIFICAMOS DUI
                         if($person->personValidations->dui_readable&&$person->personValidations->dui_name&&$person->personValidations->dui_number&&
                            $person->personValidations->dui_profession&&$person->personValidations->dui_civil_status&&$person->personValidations->dui_birth_date&&
                            $person->personValidations->dui_unexpired&&$person->personValidations->dui_unexpired){
                                dd('todo validado dui');
                            }else{
                                dd('pendiente validar dui');
                            }
                            //VERIFICAMOS NIT
                            if($person->personValidations->nit_readable&&$person->personValidations->nit_name&&$person->personValidations->nit_number){
                                dd('todo validado NIT');
                                    }else{
                                        dd('pendiente validar NIT');
                                }
                            //VERIFICAMOS Cuenta de Banco
                            if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                                dd('todo validado Banco');
                                    }else{
                                    dd('pendiente validar Banco');
                                }

                            if($person->personValidations->curriculum_readable){
                                    dd('todo validado curriculum');
                                        }else{
                                            dd('pendiente validar curriculum');
                                    }
                            if($person->personValidations->title_readable&&$person->personValidations->title_mined){
                                    dd('todo validado titulo');
                                        }else{
                                            dd('pendiente validar titulo');
                                    }
                                    if($person->personValidations->work_permission_readable){
                                        dd('todo validado permiso de trabajo');
                                            }else{
                                                dd('pendiente validar curriculum permiso de trabajo');
                                        }
                    
                }
            } else {
                //Candidato - Trabajador - Internacional 
                if ($person->employee->faculty_id == 1) {
                    dd('Empleado EXTRANJERO - TRABAJADOR DE LA FIA');
                    if($person->personValidations->passport_readable&&$person->personValidations->passport_name&&$person->personValidations->passport_number){
                        dd('todo validado pasapporte');
                    }else{
                        dd('pendiente validar pasaporte');
                    }
                    
                    //VERIFICAMOS Cuenta de Banco
                    if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                        dd('todo validado Banco');
                            }else{
                            dd('pendiente validar Banco');
                        }
    
                    if($person->personValidations->curriculum_readable){
                            dd('todo validado curriculum');
                                }else{
                                    dd('pendiente validar curriculum');
                            }
                    if($person->personValidations->title_readable&&$person->personValidations->title_apostilled
                       &&$person->personValidations->title_apostilled_redeable&&$person->personValidations->title_authentic){
                            dd('todo validado titulo');
                                }else{
                                    dd('pendiente validar titulo');
                            }
                } else {
                    dd('Empleado EXTRANJERO - TRABAJADOR DE OTRA FACULTAD');
                    if($person->personValidations->passport_readable&&$person->personValidations->passport_name&&$person->personValidations->passport_number){
                        dd('todo validado pasapporte');
                    }else{
                        dd('pendiente validar pasaporte');
                    }
                    
                    //VERIFICAMOS Cuenta de Banco
                    if($person->personValidations->bank_readable&&$person->personValidations->bank_number){
                        dd('todo validado Banco');
                            }else{
                            dd('pendiente validar Banco');
                        }
    
                    if($person->personValidations->curriculum_readable){
                            dd('todo validado curriculum');
                                }else{
                                    dd('pendiente validar curriculum');
                            }
                    if($person->personValidations->title_readable&&$person->personValidations->title_apostilled
                       &&$person->personValidations->title_apostilled_redeable&&$person->personValidations->title_authentic){
                            dd('todo validado titulo');
                                }else{
                                    dd('pendiente validar titulo');
                            }
                    if($person->personValidations->work_permission_readable){
                            dd('todo validado permiso de trabajo');
                                }else{
                                    dd('pendiente validar curriculum permiso de trabajo');
                                }        
                }
            }
        }
        
        
    }

   
}
