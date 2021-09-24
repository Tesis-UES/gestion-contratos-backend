<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Person,PersonValidation};

class PersonSeeder extends Seeder
{
    
    public function run()
    {
        //Creamo dos Usuarios para las pruebas de Candidato Nacional / Internacional
        $Nacional = User::create([
            'name'      => 'Usuario Nacional',
            'email'     => 'nacional@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $Nacional->assignRole('Candidato');

        $NacionalE = User::create([
            'name'      => 'Usuario Nacional Empleado UES',
            'email'     => 'nacionalues@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $NacionalE->assignRole('Candidato');

        $NacionalEOtra = User::create([
            'name'      => 'Usuario Nacional Empleado UES - Otra Facultad',
            'email'     => 'nacionalotra@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $NacionalEOtra->assignRole('Candidato');

        $Internacional = User::create([
            'name'      => 'Usuario Extranjero',
            'email'     => 'extranjero@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $Internacional->assignRole('Candidato');

        $InternacionalE = User::create([
            'name'      => 'Usuario Extranjero',
            'email'     => 'extranjeroEmpleado@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $InternacionalE->assignRole('Candidato');

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
                'nationality'   =>'Salvadoreño',
                'is_employee'   =>false,
                'professional_title'   =>'Ingeniero en Sistemas Informaticos',
                'nup'           =>'123456987',
                'isss_number'   =>'852159753465',
                'dui_number'    =>'05295281-3',
                'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
                'dui_expiration_date'   => '2022-01-29',
                'nit_number'    =>'0804-890597-207-8',
                'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
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
            'nationality'   =>'Salvadoreño',
            'is_employee'   =>true,
            'request_to_same_faculty'   =>true,
            'employee_type'     =>'Administrativo',
            'journey_type'  =>'Tiempo Completo',
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'nup'           =>'123456987',
            'isss_number'   =>'852159753465',
            'dui_number'    =>'052458281-3',
            'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    =>'0804-890597-207-8',
            'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
            'bank_account_number'   =>'0125415656'

        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalUesFia->id]);
        $personValidation->save();
        //Ingresando datos de candidato Nacional - Trabajador UES - Otra Facultad
        $NacionalEOtra = Person::create([
            'user_id'       => $NacionalE->id,
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
            'nationality'   =>'Salvadoreño',
            'is_employee'   =>true,
            'request_to_same_faculty'   =>false,
            'employee_type'     =>'Administrativo',
            'journey_type'  =>'Tiempo Completo',
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'nup'           =>'123456987',
            'isss_number'   =>'852159753465',
            'dui_number'    =>'052458281-3',
            'dui_text'      =>' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    =>'0804-890597-207-8',
            'nit_text'      =>'CERO OCHOCIENTOS CUATRO-OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE-CERO CERO DOSCIENTOS SIETE-OCHO ',
            'bank_account_number'   =>'0125415656'

        ]);

            $personValidation = new PersonValidation(['person_id' =>$NacionalEOtra->id]);
            $personValidation->save();

        //Ingresando Datos de Candidato Extranjero
        $Internacional =  Person::create([
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
            'is_employee'   =>false,
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Siberia',
            'nationality'   =>'Rusia',  
            'professional_title'   =>'Ingeniero en Sistemas Informaticos',
            'bank_account_number'   =>'0125415656',
        ]);
            $personValidation = new PersonValidation(['person_id' =>$Internacional->id]);
            $personValidation->save();

            $InternacionalE =  Person::create([
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
                'is_employee'   =>true,
                'request_to_same_faculty'   =>true,
                'alternate_telephone'   => '2356-8974',
                'alternate_mail'        => 'prueba@gmail.com',
                'address'       => 'Siberia',
                'nationality'   =>'Rusia',  
                'professional_title'   =>'Ingeniero en Sistemas Informaticos',
                'bank_account_number'   =>'0125415656',
            ]);
                $personValidation = new PersonValidation(['person_id' =>$InternacionalE->id]);
                $personValidation->save();
    }
}
