<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Person,PersonValidation,Employee};

class PersonSeeder extends Seeder
{
    
    public function run()
    {
        //Creamo dos Usuarios para las pruebas de Candidato Nacional / Internacional
        $Nacional = User::create([
            'name'      => 'Usuario Nacional',
            'email'     => 'nacional@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $Nacional->assignRole('Candidato');

        $NacionalE = User::create([
            'name'      => 'Usuario Nacional Empleado UES',
            'email'     => 'nacionalues@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8,
        ]);
        $NacionalE->assignRole('Candidato');
        

        $NacionalEOtra = User::create([
            'name'      => 'Usuario Nacional Empleado UES - Otra Facultad',
            'email'     => 'nacionalotra@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $NacionalEOtra->assignRole('Candidato');

        $Internacional = User::create([
            'name'      => 'Usuario Extranjero',
            'email'     => 'extranjero@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $Internacional->assignRole('Candidato');

        $InternacionalE = User::create([
            'name'      => 'Usuario Extranjero FIA',
            'email'     => 'extranjeroEmpleado@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $InternacionalE->assignRole('Candidato');

        $InternacionalEOtra = User::create([
            'name'      => 'Usuario Extranjero OTRA FACULTAD',
            'email'     => 'extranjeroEmpleadoOtra@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $InternacionalEOtra->assignRole('Candidato');

         //****************************************************************************************************** */
        //Ingresando Datos de Un Candidato NACIONAL
        $datosNacional = Person::create([
                'user_id'       => $Nacional->id,
                'first_name'    => 'Guillermo',
                'middle_name'   => 'Alexander',
                'last_name'     => 'Cornejo Argueta',
                'know_as'       => 'Pelon',
                'birth_date'    => '1996-01-18',
                'gender'        => 'Masculino',
                'civil_status'  => 'Soltero',
                'telephone'     => '2232-2009',
                'alternate_telephone'   => '2356-8974',
                'alternate_mail'        => 'prueba@gmail.com',
                'address'       => 'Colonia la malaga, calle 3, block 5',
                'department'   =>'San Salvador',
                'city'          =>'Aguilares',
                'nationality'   =>'El Salvador',
                'professional_title'   =>'Ingeniero en Sistemas Informaticos',
                'nup'           =>'123456987',
                'isss_number'   =>'852159753465',
                'dui_number'    =>'05295281-3',
                'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
                'dui_expiration_date'   => '2022-01-29',
                'nit_number'    =>'0804-890597-207-8',
                'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
                'bank_id'       => 2,
                'bank_account_number'   =>'0125415656'

        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacional->id]);
        $personValidation->save();

        //Ingresando datos de candidato Nacional - Trabajador UES - FIA
        $datosNacionalUesFia =  Person::create([
            'user_id'       => $NacionalE->id,
            'first_name'    => 'Jose',
            'middle_name'   => 'Maria',
            'last_name'     => 'Aguilar Argueta',
            'know_as'       => 'Pelonito',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Colonia la malaga, calle 3, block 5',
            'department'   =>'San Salvador',
            'city'          =>'Aguilares',
            'nationality'   =>'El Salvador',
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'nup'           =>'123456987',
            'isss_number'   =>'852159753465',
            'dui_number'    =>'052458281-3',
            'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    =>'0804-890597-207-8',
            'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
            'bank_id'       => 3,
            'bank_account_number'   =>'0125415656'

        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalUesFia->id]);
        $personValidation->save();
        //Ingresando datos de empleado 
        Employee::create([
            'journey_type'      =>'tiempo-completo',
            'faculty_id'        => 1,
            'escalafon_id'      => 1,
            'employee_type_id'  => 1,
            'person_id'         => $datosNacionalUesFia->id,
        ]);

        //Ingresando datos de candidato Nacional - Trabajador UES - Otra Facultad
        $datosNacionalEOtra = Person::create([
            'user_id'       => $NacionalEOtra->id,
            'first_name'    => 'Jose',
            'middle_name'   => 'bennet',
            'last_name'     => 'Corderjo Argueta',
            'know_as'       => 'Pelon',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Colonia la malaga, calle 3, block 5',
            'department'   =>'San Salvador',
            'city'          =>'Aguilares',
            'nationality'   =>'El Salvador',
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'nup'           =>'123456987',
            'isss_number'   =>'852159753465',
            'dui_number'    =>'052458281-3',
            'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    =>'0804-890597-207-8',
            'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
            'bank_id'       => 4,
            'bank_account_number'   =>'0125415656'

        ]);

            $personValidation = new PersonValidation(['person_id' =>$datosNacionalEOtra ->id]);
            $personValidation->save();

            Employee::create([
            'journey_type'      =>'tiempo-completo',
            'faculty_id'        => 2,
            'escalafon_id'      => 1,
            'employee_type_id'  => 1,
            'person_id'         => $datosNacionalEOtra ->id,
                ]);
        
        //Ingresando Datos de Candidato Extranjero
        $DatosInternacional =  Person::create([
            'user_id'       => $Internacional->id,
            'first_name'    => 'Vladimir',
            'middle_name'   => 'irvetzeliv',
            'last_name'     => 'bliat',
            'know_as'       => 'RUSO',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'passport_number'=>'1616841641684',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Siberia',
            'nationality'   =>'Rusia',  
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'bank_id'       => 5,
            'bank_account_number'   =>'0125415656',
        ]);
            $personValidation = new PersonValidation(['person_id' =>$DatosInternacional->id]);
            $personValidation->save();

            $DatosInternacionalE =  Person::create([
                'user_id'       => $InternacionalE->id,
                'first_name'    => 'Vladimir',
                'middle_name'   => 'irvetzeliv',
                'last_name'     => 'bliat',
                'know_as'       => 'Bielorusia',
                'birth_date'    => '1996-01-18',
                'gender'        => 'Masculino',
                'civil_status'  => 'Soltero',
                'telephone'     => '2232-2009',
                'passport_number'=>'16168416416846854',
                'alternate_telephone'   => '2356-8974',
                'alternate_mail'        => 'prueba@gmail.com',
                'address'       => 'Siberia',
                'nationality'   =>'Rusia',  
                'professional_title'   =>'Ingeniero en Sistemas Informaticos',
                'bank_id'       => 6,
                'bank_account_number'   =>'0125415656',
            ]);
                $personValidation = new PersonValidation(['person_id' =>$DatosInternacionalE->id]);
                $personValidation->save();

                Employee::create([
                    'journey_type'      =>'tiempo-completo',
                    'faculty_id'        => 1,
                    'escalafon_id'      => 1,
                    'employee_type_id'  => 1,
                    'person_id'         =>$DatosInternacionalE->id,
                        ]);

        $DatosInternacionalEO =  Person::create([
                            'user_id'       => $InternacionalEOtra->id,
                            'first_name'    => 'Cordelio',
                            'middle_name'   => 'irvetzeliv',
                            'last_name'     => 'bliat',
                            'know_as'       => 'Bielorusia',
                            'birth_date'    => '1996-01-18',
                            'gender'        => 'Masculino',
                            'civil_status'  => 'Soltero',
                            'telephone'     => '2232-2009',
                            'passport_number'=>'16168416416846854',
                            'alternate_telephone'   => '2356-8974',
                            'alternate_mail'        => 'prueba@gmail.com',
                            'address'       => 'Siberia',
                            'nationality'   =>'España',  
                            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
                            'bank_id'       => 7,
                            'bank_account_number'   =>'0125415656',
         ]);
        $personValidation = new PersonValidation(['person_id' =>$DatosInternacionalEO->id]);
        $personValidation->save();
            
        Employee::create([
                        'journey_type'      =>'tiempo-completo',
                        'faculty_id'        => 2,
                        'escalafon_id'      => 1,
                        'employee_type_id'  => 2,
                        'person_id'         => $DatosInternacionalEO->id,
                    ]);
    }
}
