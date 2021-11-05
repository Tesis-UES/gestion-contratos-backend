<?php

namespace App\Http\Traits;
use App\Models\Person;

trait ValidationTrait{


    public function updatePersonStatus(Person $person){

        //$person = Person::findOrFail($id);
         if ($person->employee == null) {
            //Si no es empleado verificamos que sea nacional o extanjero
            if ($person->nationality == 'El Salvador') {
                if ($this->allFilesNational($person)) {
                    if ($this->validatedNational($person)) {
                        $status = 'Validado';
                    } else {
                        $status = 'Pendiente';
                    }
                } else {
                    $status = 'Registrando';
                }
            } else {
                //EXTRANJERO
                if ($this-> allFilesInternational($person)) {
                    if ($this->validatedInternational($person)) {
                        $status = 'Validado';
                    } else {
                        $status = 'Pendiente';
                    }
                } else {
                    $status = 'Registrando';
                }
            }
        } else {
            //Candidato - Trabajador
            if ($person->nationality == 'El Salvador') {
                //Candidato - Trabajador - Nacional
                if ($person->employee->faculty_id == 1) {
                    if ($this->allFilesNational($person)) {
                        if ($this->validatedNational($person)) {
                            $status = 'Validado';
                        } else {
                            $status = 'Pendiente';
                        }
                    } else {
                        $status = 'Registrando';
                    }
                } else {
                    if ($this->allFilesNational($person)&&$this->workPermissionFile($person)) {
                        if ($this->validatedNational($person)&&$this->validateWorkPermission($person)) {
                            $status = 'Validado';
                        } else {
                            $status = 'Pendiente';
                        }
                    } else {
                        $status = 'Registrando';
                    }
                    
                }
            } else {
                //Candidato - Trabajador - Internacional 
                 if ($person->employee->faculty_id == 1) {
                    if ($this-> allFilesInternational($person)) {
                        if ($this->validatedInternational($person)) {
                            $status = 'Validado';
                        } else {
                            $status = 'Pendiente';
                        }
                    } else {
                        $status = 'Registrando';
                    }
                } else {
                    if ($this-> allFilesInternational($person)&&$this->workPermissionFile($person)) {
                        if ($this->validatedInternational($person)&&$this->validateWorkPermission($person)) {
                            $status = 'Validado';
                        } else {
                            $status = 'Pendiente';
                        }
                    } else {
                        $status = 'Registrando';
                    }
                } 
            }
        }

        $person->status = $status;
        $person->save();
    }

    private function allFilesNational(Person $person){

        if ($person->is_nationalized == true) {
           
            if ($person->other_title == true) {
                return $person->resident_card != null && 
                $person->nit != null && 
                $person->curriculum != null && 
                $person->bank_account != null && 
                $person->professional_title_scan != null &&
                $person->other_title_doc != null;
            }else{
                return $person->resident_card != null && 
                $person->nit != null && 
                $person->curriculum != null && 
                $person->bank_account != null && 
                $person->professional_title_scan != null;
            }
        }else{
            if ($person->other_title == true) {
                return  $person->dui != null && 
                $person->nit != null && 
                $person->curriculum != null && 
                $person->bank_account != null && 
                $person->professional_title_scan != null&&
                $person->other_title_doc != null;;
            }else{
                return  $person->dui != null && 
                $person->nit != null && 
                $person->curriculum != null && 
                $person->bank_account != null && 
                $person->professional_title_scan != null;
            }
        }

       
    }

    private function validatedNational(Person $person){
        return $person->personValidations->dui_readable && 
               $person->personValidations->dui_name && 
               $person->personValidations->dui_number && 
               $person->personValidations->dui_profession && 
               $person->personValidations->dui_civil_status && 
               $person->personValidations->dui_birth_date && 
               $person->personValidations->dui_unexpired && 
               $person->personValidations->dui_address &&
               $person->personValidations->nit_readable && 
               $person->personValidations->nit_name && 
               $person->personValidations->nit_number &&
               $person->personValidations->bank_readable &&
               $person->personValidations->bank_number &&
               $person->personValidations->curriculum_readable &&
               $person->personValidations->title_readable && 
               $person->personValidations->title_mined;
    }

    private function allFilesInternational(Person $person){
        return  $person->passport != null && 
                $person->curriculum != null && 
                $person->bank_account != null && 
                $person->professional_title_scan != null;
    }

    private function validatedInternational(Person $person){
        return  $person->personValidations->passport_readable && 
        $person->personValidations->passport_name && 
        $person->personValidations->passport_number && 
        $person->personValidations->bank_readable && 
        $person->personValidations->bank_number && 
        $person->personValidations->curriculum_readable && 
        $person->personValidations->title_readable && 
        $person->personValidations->title_apostilled && 
        $person->personValidations->title_apostilled_readable && 
        $person->personValidations->title_authentic;
    }

    private function workPermissionFile(Person $person){
        return  $person->work_permission != null;
    }

    private function validateWorkPermission(Person $person){
        return $person->personValidations->work_permission_readable;
    } 
}
