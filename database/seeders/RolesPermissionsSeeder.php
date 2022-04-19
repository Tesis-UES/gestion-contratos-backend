<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class RolesPermissionsSeeder extends Seeder
{
    use  HasRoles;
    public function run()
    {
        $usuario1 = User::create([
            'name'      => 'Admin',
            'email'     => 'admin@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);

        $usuario2 = User::create([
            'name'      => 'Guillermo Alexander Cornejo Argueta',
            'email'     => 'admin2@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $usuario3 = User::create([
            'name'      => 'Paola Renee Aguilar Quevedo',
            'email'     => 'paola@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8,
        ]);
        $usuario4 = User::create([
            'name'      => 'Oscar Emmanuel Cordero Hernandez',
            'email'     => 'oscar@ues.edu.sv',
            'password'  => bcrypt('foobar'),
            'school_id' => 8,
        ]);
        $usuario5 = User::create([
            'name'      => 'Walter Baudilio Luna Peñate',
            'email'     => 'walter@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $usuario6 = User::create([
            'name'      => 'Juan Perez sosa',
            'email'     => 'juan@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);

        $usuario7 = User::create([
            'name'      => 'Recursos Humanos',
            'email'     => 'rrhh@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        $decano = User::create([
            'name'      => 'Decano',
            'email'     => 'decano@ues.edu.sv',
            'password'  => bcrypt('foobar'),
        ]);
        // CREACION DE PERMISOS 
        Permission::create(['name' => 'change_passwords']);
        Permission::create(['name' => 'write_formats']);

        Permission::create(['name' => 'read_worklog']);
        Permission::create(['name' => 'read_roles']);

        Permission::create(['name' => 'read_escalafones']);
        Permission::create(['name' => 'write_escalafones']);

        Permission::create(['name' => 'read_faculties']);
        Permission::create(['name' => 'write_faculties']);

        Permission::create(['name' => 'read_schools']);
        Permission::create(['name' => 'write_schools']);

        Permission::create(['name' => 'read_activities']);
        Permission::create(['name' => 'write_activities']);

        Permission::create(['name' => 'read_positions']);
        Permission::create(['name' => 'write_positions']);

        Permission::create(['name' => 'read_courses']);
        Permission::create(['name' => 'write_courses']);

        Permission::create(['name' => 'read_contractTypes']);
        Permission::create(['name' => 'write_contractTypes']);

        Permission::create(['name' => 'read_centralAuthorities']);
        Permission::create(['name' => 'write_centralAuthorities']);

        Permission::create(['name' => 'read_persons']);
        Permission::create(['name' => 'write_persons']);

        Permission::create(['name' => 'write_users']);
        Permission::create(['name' => 'read_users']);

        Permission::create(['name' => 'write_plans']);
        Permission::create(['name' => 'read_plans']);

        Permission::create(['name' => 'write_facultyAuth']);
        Permission::create(['name' => 'read_facultyAuth']);

        Permission::create(['name' => 'write_schoolAuth']);
        Permission::create(['name' => 'read_schoolAuth']);

        Permission::create(['name' => 'write_semesters']);
        Permission::create(['name' => 'read_semesters']);

        Permission::create(['name' => 'write_academicLoad']);
        Permission::create(['name' => 'read_academicLoad']);

        Permission::create(['name' => 'write_groups']);
        Permission::create(['name' => 'read_groups']);

        Permission::create(['name' => 'write_groupsType']);
        Permission::create(['name' => 'read_groupsType']);

        Permission::create(['name' => 'write_personValidations']);
        Permission::create(['name' => 'read_personValidations']);

        Permission::create(['name' => 'write_employee']);
        Permission::create(['name' => 'read_employee']);

        Permission::create(['name' => 'write_staySchedule']);
        Permission::create(['name' => 'read_staySchedule']);

        Permission::create(['name' => 'write_employeeType']);
        Permission::create(['name' => 'read_employeeType']);

        Permission::create(['name' => 'read_banks']);
        Permission::create(['name' => 'write_banks']);

        Permission::create(['name' => 'read_hiringRequest']);
        Permission::create(['name' => 'write_hiringRequest']);

        Permission::create(['name' => 'read_agreements']);
        Permission::create(['name' => 'write_agreements']);

        Permission::create(['name' => 'read_hiringRequestsHR']);
        Permission::create(['name' => 'write_hiringRequestsHR']);

        Permission::create(['name' => 'read_contracts']);
        Permission::create(['name' => 'write_contracts']);

        // Permisos individuales 
        Permission::create(['name' => 'read_myHiringRequests']);
        Permission::create(['name' => 'read_dashboard']);
        Permission::create(['name' => 'read_reports']);

        //Permisos visuales
        Permission::create(['name' => 'view_activities']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'view_worklog']);
        Permission::create(['name' => 'view_centralAuthorities']);
        Permission::create(['name' => 'view_catalogs']);
        Permission::create(['name' => 'view_myInfo']);
        Permission::create(['name' => 'view_uploadDoc']);
        Permission::create(['name' => 'view_updateDocs']);
        Permission::create(['name' => 'view_solicitudeDirector']);
        Permission::create(['name' => 'view_academicLoadDirector']);
        Permission::create(['name' => 'view_academicLoad']);
        Permission::create(['name' => 'view_personInfo']);
        Permission::create(['name' => 'view_contracSolicitude']);
        Permission::create(['name' => 'view_Personal_changes']);
        Permission::create(['name' => 'view_rrhh']);
        Permission::create(['name' => 'view_candidates']);
        Permission::create(['name' => 'view_request_asis']);
        Permission::create(['name' => 'accept_request_asis']);
        Permission::create(['name' => 'view_rrhhContract']);
        Permission::create(['name' => 'view_hiringRequest']);
        Permission::create(['name' => 'read_validated_requests']);
        Permission::create(['name' => 'list_employees']);

        //CREACION DE ROLES
        $admin = Role::create(['name' => 'Administrador']);
        $profesor = Role::create(['name' => 'Candidato']);
        $directorEscuela = Role::create(['name' => 'Director Escuela']);
        $asistenteAdmin = Role::create(['name' => 'Asistente Administrativo']);
        $financiero =   Role::create(['name' => 'Asistente Financiero']);
        $rrhh = Role::create(['name' => 'Recursos Humanos']);
        $decanoRole = Role::create(['name' => 'Decano']);

        $admin->givePermissionTo([
            'change_passwords',
            'read_worklog',
            'read_roles',
            'write_employeeType',
            'read_employeeType',
            'read_escalafones',
            'write_escalafones',
            'read_faculties',
            'write_faculties',
            'read_schools',
            'write_schools',
            'read_activities',
            'write_activities',
            'read_positions',
            'write_positions',
            'read_courses',
            'write_courses',
            'read_contractTypes',
            'write_contractTypes',
            'read_centralAuthorities',
            'write_centralAuthorities',
            'read_users',
            'write_users',
            'write_plans',
            'read_plans',
            'write_facultyAuth',
            'read_facultyAuth',
            'write_schoolAuth',
            'read_schoolAuth',
            'write_semesters',
            'read_semesters',
            'write_groupsType',
            'read_groupsType',
            'read_academicLoad',
            'read_groups',
            'write_personValidations',
            'read_personValidations',
            'write_formats',
            'view_users',
            'view_worklog',
            'view_centralAuthorities',
            'view_catalogs',
            'view_academicLoad',
            'read_banks',
            'write_banks',
            'read_hiringRequest',
            'read_agreements',
            'read_dashboard',
            'read_reports',
            'view_candidates',

        ]);

        $profesor->givePermissionTo([
            'read_employeeType',
            'read_escalafones',
            'read_faculties',
            'read_schools',
            'read_activities',
            'write_activities',
            'read_positions',
            'read_courses',
            'read_contractTypes',
            'read_persons',
            'write_persons',
            'read_plans',
            'read_semesters',
            'read_groupsType',
            'read_academicLoad',
            'read_groups',
            'read_personValidations',
            'write_employee',
            'read_employee',
            'write_staySchedule',
            'read_staySchedule',
            'view_myInfo',
            'view_uploadDoc',
            'view_updateDocs',
            'view_Personal_changes',
            'read_banks',
            'read_myHiringRequests',
        ]);

        $directorEscuela->givePermissionTo([
            'read_employeeType',
            'read_escalafones',
            'read_faculties',
            'read_schools',
            'read_activities',
            'write_activities',
            'read_positions',
            'read_courses',
            'read_contractTypes',
            'read_persons',
            'read_plans',
            'read_facultyAuth',
            'read_schoolAuth',
            'read_semesters',
            'read_groupsType',
            'write_academicLoad',
            'read_academicLoad',
            'write_groups',
            'read_groups',
            'view_solicitudeDirector',
            'view_academicLoadDirector',
            'read_hiringRequest',
            'write_hiringRequest',
            'view_hiringRequest',
            'read_dashboard',
            'read_agreements',
        ]);

        $asistenteAdmin->givePermissionTo([
            'read_hiringRequest',
            'view_request_asis',
            'accept_request_asis',
            'list_employees',
            'read_agreements',
            'write_agreements',
            'read_reports',
            'read_contractTypes',
            'view_candidates',
            'read_dashboard',
        ]);

        $rrhh->givePermissionTo([
            'view_rrhh',
            'view_candidates',
            'write_personValidations',
            'read_personValidations',
            'view_rrhhContract',
            'read_hiringRequest',
            'read_schools',
            'read_agreements',
            'write_hiringRequestsHR',
            'read_hiringRequestsHR',
            'read_contracts',
            'write_contracts',
            'read_dashboard',
            'read_reports',
            'read_contractTypes',
        ]);

        $financiero->givePermissionTo([
            'view_candidates',
            'read_validated_requests',
            'read_escalafones',
            'read_hiringRequest',
            'read_banks',
            'read_agreements',
            'read_reports',
            'read_contractTypes',
        ]);

        $decanoRole->givePermissionTo([
            'read_schools',
            'read_semesters',
            'read_contractTypes',
            'read_agreements',
            'read_dashboard',
            'read_reports',
            'view_candidates',
            'view_request_asis',
            'read_agreements',
            'read_hiringRequest',
        ]);

        $usuario1->assignRole('Administrador');
        $usuario2->assignRole('Administrador');
        $usuario3->assignRole('Candidato');
        $usuario4->assignRole('Director Escuela');
        $usuario5->assignRole('Asistente Administrativo');
        $usuario6->assignRole('Asistente Financiero');
        $usuario7->assignRole('Recursos Humanos');
        $decano->assignRole('Decano');
    }
}
