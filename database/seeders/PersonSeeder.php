<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Person, PersonValidation, Employee, EmployeeType, StaySchedule, Semester, Activity};
use App\Http\Controllers\PersonController;

class PersonSeeder extends Seeder
{

    public function run()
    {
        $task = new PersonController;
      
        // Obtenemos employeeTypes,semesters,users
        Semester::Create(['name' => 'Ciclo 1-2022', 'start_date' => '2022-02-14', 'end_date' => '2022-06-10', 'status' => true]);
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
            'dui_expiration_date'   => '2022-09-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE-OCHO ',
            'bank_id'       => 2,
            'is_employee' => false,
            'is_nationalized' => false,
            'bank_account_number'   => '0125415656',
            'bank_account_type'     => 'Cuenta de ahorro',
            'curriculum' => 'guillermocornejoCV.pdf',
            'nit' => 'NIT.pdf',
            'dui' => 'DUI.pdf',	
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacional->id]);
        $personValidation->save();
        $r = $task->mergePersonalDoc($datosNacional->id);

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
            'bank_account_type'     => 'Cuenta de ahorro',
            'is_nationalized' => true,
            'is_employee' => false,
            'resident_card_number'      => '6044785',
            'resident_card_text'        => 'NUMERO X',
            'resident_expiration_date' => '2022-01-29',
            'other_title'   => true,
            'other_title_name' => 'ingeniero en petroleo',
            'curriculum' => 'joseperezCV.pdf',
            'nit' => 'NIT.pdf',
            'other_title_doc' => 'OTRO-T.pdf',
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
            'resident_card' => 'CARNET.pdf',
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalizado->id]);
        $personValidation->save();
        $r = $task->mergePersonalDoc($datosNacionalizado->id);


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
            'dui_expiration_date'   => '2022-09-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE - OCHO ',
            'bank_id'       => 3,
            'bank_account_number'   => '0125415656',
            'bank_account_type'     => 'Cuenta de ahorro',
            'is_employee' => true,
            'is_nationalized' => false,
            'curriculum' => 'joseramirezCV.pdf',
            'nit' => 'NIT.pdf',
            'dui' => 'DUI.pdf',	
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
        ]);
        $personValidation = new PersonValidation(['person_id' => $datosNacionalUesFia->id]);
        $personValidation->save();

        //Ingresando datos de empleado 
        $employeeNacionalUesFia = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 1,
            'escalafon_id'      => 1,
            'person_id'         => $datosNacionalUesFia->id,
            'sub_partida'       => '1',
            'partida'           => '43',
        ]);
        $employeeNacionalUesFia->employeeTypes()->save($employeeTypes[0]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeNacionalUesFia->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
        $r = $task->mergePersonalDoc($datosNacionalUesFia->id);

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
            'dui_expiration_date'   => '2022-09-29',
            'nit_number'    => '0804-890597-207-8',
            'nit_text'      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE- DOSCIENTOS SIETE - OCHO ',
            'bank_id'       => 4,
            'bank_account_number'   => '0125415656',
            'bank_account_type'     => 'Cuenta de ahorro',
            'is_employee' => true,
            'is_nationalized' => false,
            'curriculum' => 'joselopezCV.pdf',
            'nit' => 'NIT.pdf',
            'dui' => 'DUI.pdf',	
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
            'work_permission' => 'PERMISO.pdf',
        ]);

        $personValidation = new PersonValidation(['person_id' => $datosNacionalEOtra->id]);
        $personValidation->save();


        $employeeNacionalEOtra = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 2,
            'escalafon_id'      => 1,
            'person_id'         => $datosNacionalEOtra->id,
            'sub_partida'       => '1',
            'partida'           => '43',
        ]);
        $employeeNacionalEOtra->employeeTypes()->saveMany([$employeeTypes[0], $employeeTypes[1]]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeNacionalEOtra->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
        $r = $task->mergePersonalDoc($datosNacionalEOtra->id);

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
            'is_employee' => false,
            'is_nationalized' => false,
            'bank_account_number'   => '0125415656',
            'bank_account_type'     => 'Cuenta de ahorro',
            'curriculum' => 'jhoanjhonsonCV.pdf',
            'passport' => 'PASAPORTE.pdf',
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
            
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacional->id]);
        $personValidation->save();
        $r = $task->mergePersonalDoc($DatosInternacional->id);

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
            'bank_account_type'     => 'Cuenta de ahorro',
            'is_employee' => true,
            'is_nationalized' => false,
            'curriculum' => 'vladimirbobrovCV.pdf',
            'passport' => 'PASAPORTE.pdf',
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacionalE->id]);
        $personValidation->save();


        $employeeInternacionalE = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 1,
            'escalafon_id'      => 1,
            'person_id'         => $DatosInternacionalE->id,
            'sub_partida'       => '1',
            'partida'           => '43',
        ]);
        $employeeInternacionalE->employeeTypes()->save($employeeTypes[2]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeInternacionalE->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
        $r = $task->mergePersonalDoc($DatosInternacionalE->id);


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
            'bank_account_type'     => 'Cuenta de ahorro',
            'is_employee' => true,
            'is_nationalized' => false,
            'curriculum' => 'michaeljordanCV.pdf',
            'passport' => 'PASAPORTE.pdf',
            'bank_account' => 'BANCO.pdf',
            'professional_title_scan' => 'TITULO.pdf',
            'work_permission' => 'PERMISO.pdf'
        ]);
        $personValidation = new PersonValidation(['person_id' => $DatosInternacionalEO->id]);
        $personValidation->save();

        $employeeInternacionalEO = Employee::create([
            'journey_type'      => 'tiempo-completo',
            'faculty_id'        => 2,
            'escalafon_id'      => 1,
            'person_id'         => $DatosInternacionalEO->id,
            'sub_partida'       => '1',
            'partida'           => '43',
        ]);
        $employeeInternacionalEO->employeeTypes()->save($employeeTypes[1]);
        $newStaySchedule = StaySchedule::create([
            'semester_id'   => $semester->id,
            'employee_id'  => $employeeInternacionalEO->id,
        ]);

        $newStaySchedule->scheduleDetails()->createMany($details);
        $newStaySchedule->scheduleActivities()->sync($activityIds);
        $r = $task->mergePersonalDoc($DatosInternacionalEO->id);

    }
}
