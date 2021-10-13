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

        Permission::create(['name' => 'view_rrhh']);
        Permission::create(['name' => 'view_candidates']);
      
        
        //Permisos visuales
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
     

        
        //CREACION DE ROLES
        $admin = Role::create(['name' => 'Administrador']);
        $profesor = Role::create(['name' => 'Candidato']);
        $directorEscuela = Role::create(['name' => 'Director Escuela']);
        $asistenteAdmin = Role::create(['name' => 'Asistente Administrativo']);
        $financiero =   Role::create(['name' => 'Asistente Financiero']);
        $rrhh = Role::create(['name' => 'Recursos Humanos']);



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
            'view_academicLoad'
        ]);

        $profesor->givePermissionTo([
            'read_employeeType',
            'read_escalafones',
            'read_faculties',
            'read_schools',
            'read_activities',
            'write_activities',
            'read_courses',
            'read_contractTypes',
            'read_persons',
            'write_persons',
            'read_plans',
            'read_facultyAuth',   
            'read_schoolAuth',
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
        ]);

        $directorEscuela->givePermissionTo([
            'read_employeeType',
            'read_escalafones',
            'read_faculties',
            'read_schools',
            'read_activities',
            'write_activities',
            'read_courses',
            'write_courses',
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
        ]);

        $asistenteAdmin->givePermissionTo([
            'read_employeeType',
            'read_escalafones',
            'write_escalafones',
            'read_faculties',
            'write_faculties',
            'read_schools',
            'write_schools',
            'read_activities',
            'write_activities',
            'read_courses',
            'write_courses',
            'read_contractTypes',
            'write_contractTypes',
            'read_centralAuthorities',
            'write_centralAuthorities',
            'read_plans',
            'read_facultyAuth',
            'read_schoolAuth',
            'read_semesters',
            'read_groupsType',
            'read_academicLoad',
            'read_groups',
            'write_personValidations',
            'read_personValidations',
            'view_personInfo',
            'view_contracSolicitude',
            'view_candidates',
        ]);

        $rrhh->givePermissionTo([
            'view_rrhh',
            'view_candidates',
            'write_personValidations',
            'read_personValidations'
        ]);

        $financiero->givePermissionTo([
            'view_candidates',
        ]);

        $usuario1->assignRole('Administrador');
        $usuario2->assignRole('Administrador');
        $usuario3->assignRole('Candidato');
        $usuario4->assignRole('Director Escuela');
        $usuario5->assignRole('Asistente Administrativo');
        $usuario6->assignRole('Asistente Financiero');
        $usuario7->assignRole('Recursos Humanos');
    }
}
