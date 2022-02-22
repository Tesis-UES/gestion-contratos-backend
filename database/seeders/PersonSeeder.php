<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Person, PersonValidation, Employee, EmployeeType, StaySchedule, Semester, Activity};

class PersonSeeder extends Seeder
{

    public function run()
    {
        // Obtenemos employeeTypes,semesters,users
        Semester::Create(['name' => 'Ciclo 2-2021', 'start_date' => '2021-07-13', 'end_date' => '2021-12-12', 'status' => true]);
        $employeeTypes = EmployeeType::all();
        $semester = Semester::firstWhere('status', 1);
        //detalles de horario
        $details = [
            [
                "day" => "Lunes",
                "start_time" => "07:00",
                "finish_time" => "12:00"
            ],
            [
                "day" => "Lunes",
                "start_time" => "13:00",
                "finish_time" => "16:00"
            ],
            [
                "day" => "Martes",
                "start_time" => "07:00",
                "finish_time" => "12:00"
            ],
            [
                "day" => "Martes",
                "start_time" => "13:00",
                "finish_time" => "16:00"
            ],
            [
                "day" => "Miercoles",
                "start_time" => "07:00",
                "finish_time" => "12:00"
            ],
            [
                "day" => "Miercoles",
                "start_time" => "13:00",
                "finish_time" => "16:00"
            ],
            [
                "day" => "Jueves",
                "start_time" => "07:00",
                "finish_time" => "12:00"
            ],
            [
                "day" => "Jueves",
                "start_time" => "13:00",
                "finish_time" => "16:00"
            ],
            [
                "day" => "Viernes",
                "start_time" => "07:00",
                "finish_time" => "12:00"
            ],
            [
                "day" => "Viernes",
                "start_time" => "13:00",
                "finish_time" => "16:00"
            ]
        ];
        $activities = [
            "Programar y coordinar el uso de equipo de laboratorio",
            "Asesorar el uso de equipo, insumos y materiales diversos",
            "Monitorear e informar sobre la necesidad de insuos",
            "Impartir cursos, talleres, charlas y ponencias",
            "Promover y dar apoyo en estudios de investigacion tecnologica",
            "Administrar evaluaciones",
            "Calificar evaluaciones",
            "Atender consultas",
            "Impartir discusiones de la asignatura",
            "Preparar material de apoyo",
            "Elaborar evlualaciones"
        ];
        foreach ($activities as $activityName) {
            $activity = Activity::where('name', 'ilike', $activityName)->first();
            if (!$activity) {
                $activity = Activity::create(['name' => $activityName]);
            }
            $activityIds[] = $activity->id;
        }

        //Creamo dos Usuarios para las pruebas de Candidato Nacional / Internacional
        $Nacional = User::create([
            'name'      => 'Usuario Nacional',
            'email'     => 'nacional@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $Nacional->assignRole('Candidato');

        $Nacionalizado = User::create([
            'name'      => 'Usuario Nacionalizado',
            'email'     => 'nacionalizado@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8
        ]);
        $Nacionalizado->assignRole('Candidato');

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
            'status'        => 'Validado',
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
            'department'   => 'San Salvador',
            'city'          => 'Aguilares',
            'nationality'   => 'El Salvador',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'nup'           => '123456987',
            'isss_number'   => '852159753465',
            'dui_number'    => '05295281-3',
            'dui_text'      => ' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE-OCHO ',
            'bank_id'       => 2,
            'bank_account_number'   => '0125415656'
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacional->id]);
        $personValidation->save();

        /*********************************************************************************************/
        //Ingresando Datos Nacionalizado//
        $datosNacionalizado = Person::create([
            'user_id'       => $Nacionalizado->id,
            'status'        => 'Validado',
            'first_name'    => 'Jose',
            'middle_name'   => 'Alejandro',
            'last_name'     => 'Perez Perdomo',
            'know_as'       => 'alejandrino',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Colonia la malaga, calle 3, block 5',
            'department'   => 'San Salvador',
            'city'          => 'Mejicanos',
            'nationality'   => 'El Salvador',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'nup'           => '123456987',
            'isss_number'   => '852159753465',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE - OCHO ',
            'bank_id'       => 2,
            'bank_account_number'   => '0125415656',
            'is_nationalized' => true,
            'resident_card_number'      => '6044785',
            'resident_card_text'        => 'NUMERO X',
            'resident_expiration_date' => '2022-01-29',
            'other_title'   => true,
            'other_title_name' => 'ingeniero en petroleo',
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalizado->id]);
        $personValidation->save();

        //Ingresando datos de candidato Nacional - Trabajador UES - FIA
        $datosNacionalUesFia =  Person::create([
            'user_id'       => $NacionalE->id,
            'status'        => 'Validado',
            'first_name'    => 'Jose',
            'middle_name'   => 'Roberto',
            'last_name'     => 'Ramirez Argueta',
            'know_as'       => 'Adalberto',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Colonia la malaga, calle 3, block 5',
            'department'   => 'San Salvador',
            'city'          => 'Ayutuxtepeque',
            'nationality'   => 'El Salvador',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'nup'           => '123456987',
            'isss_number'   => '852159753465',
            'dui_number'    => '052458281-3',
            'dui_text'      => ' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE - OCHO ',
            'bank_id'       => 3,
            'bank_account_number'   => '0125415656',
            'is_employee' => true
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalUesFia->id]);
        $personValidation->save();
        //Ingresando datos de empleado 
        $employeeNacionalUesFia = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 1,
            'escalafon_id'      => 1,
            'person_id'         => $datosNacionalUesFia->id,
        ]);
        $employeeNacionalUesFia->employeeTypes()->save($employeeTypes[0]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeNacionalUesFia->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);

        //Ingresando datos de candidato Nacional - Trabajador UES - Otra Facultad
        $datosNacionalEOtra = Person::create([
            'user_id'       => $NacionalEOtra->id,
            'status'        => 'Validado',
            'first_name'    => 'Jose',
            'middle_name'   => 'Maria',
            'last_name'     => 'Lopez Doriga',
            'know_as'       => 'Guille',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Colonia la malaga, calle 3, block 5',
            'department'   => 'San Salvador',
            'city'          => 'Apopa',
            'nationality'   => 'El Salvador',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'nup'           => '123456987',
            'isss_number'   => '852159753465',
            'dui_number'    => '052458281-3',
            'dui_text'      => ' CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
            'dui_expiration_date'   => '2022-01-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE- DOSCIENTOS SIETE - OCHO ',
            'bank_id'       => 4,
            'bank_account_number'   => '0125415656',
            'is_employee' => true
        ]);

        $personValidation = new PersonValidation(['person_id' => $datosNacionalEOtra->id]);
        $personValidation->save();

        $employeeNacionalEOtra = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 2,
            'escalafon_id'      => 1,
            'person_id'         => $datosNacionalEOtra->id,
        ]);
        $employeeNacionalEOtra->employeeTypes()->saveMany([$employeeTypes[0], $employeeTypes[1]]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeNacionalEOtra->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
        //Ingresando Datos de Candidato Extranjero
        $DatosInternacional =  Person::create([
            'user_id'       => $Internacional->id,
            'status'        => 'Validado',
            'first_name'    => 'Jhoan',
            'middle_name'   => 'irvetzeliv',
            'last_name'     => 'Jhonson Roberts',
            'know_as'       => 'Kronos',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'passport_number' => '24573848445',
            'telephone'     => '2232-2009',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Siberia',
            'nationality'   => 'Rusia',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'bank_id'       => 5,
            'bank_account_number'   => '0125415656',
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacional->id]);
        $personValidation->save();

        $DatosInternacionalE =  Person::create([
            'user_id'       => $InternacionalE->id,
            'status'        => 'Validado',
            'first_name'    => 'Vladimir',
            'middle_name'   => 'ivanovich',
            'last_name'     => 'bobrov ivanova',
            'know_as'       => 'Bielorusia',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'passport_number' => '16168416416846854',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Siberia',
            'nationality'   => 'Rusia',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'bank_id'       => 6,
            'bank_account_number'   => '0125415656',
            'is_employee' => true
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacionalE->id]);
        $personValidation->save();

        $employeeInternacionalE = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 1,
            'escalafon_id'      => 1,
            'person_id'         => $DatosInternacionalE->id,
        ]);
        $employeeInternacionalE->employeeTypes()->save($employeeTypes[2]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeInternacionalE->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);

        $DatosInternacionalEO =  Person::create([
            'user_id'       => $InternacionalEOtra->id,
            'status'        => 'Validado',
            'first_name'    => 'Michael',
            'middle_name'   => 'Stanley',
            'last_name'     => 'Jordan steward',
            'know_as'       => 'MSJ',
            'birth_date'    => '1996-01-18',
            'gender'        => 'Masculino',
            'civil_status'  => 'Soltero',
            'telephone'     => '2232-2009',
            'passport_number' => '1616841641',
            'alternate_telephone'   => '2356-8974',
            'alternate_mail'        => 'prueba@gmail.com',
            'address'       => 'Siberia',
            'nationality'   => 'Estados Unidos',
            'professional_title'   => 'Ingeniero en Sistemas Informaticos',
            'bank_id'       => 7,
            'bank_account_number'   => '0125415656',
            'is_employee' => true
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacionalEO->id]);
        $personValidation->save();

        $employeeInternacionalEO = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 2,
            'escalafon_id'      => 1,
            'person_id'         => $DatosInternacionalEO->id,
        ]);
        $employeeInternacionalEO->employeeTypes()->save($employeeTypes[1]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeInternacionalEO->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
    }
}
