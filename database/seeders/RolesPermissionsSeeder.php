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





        // CREACION DE PERMISOS 
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
     

        
        //CREACION DE ROLES
        $admin = Role::create(['name' => 'Administrador']);
        $profesor = Role::create(['name' => 'Profesor']);
        $directorEscuela = Role::create(['name' => 'Director Escuela']);
        $asistenteAdmin = Role::create(['name' => 'Asistente Administrativo']);
        $financiero =   Role::create(['name' => 'Asistente Financiero']);



        $admin->givePermissionTo([
            'read_worklog',
            'read_roles',
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
            'view_users',
            'view_worklog',
            'view_centralAuthorities',
            'view_catalogs',
            'view_academicLoad'
        ]);

        $profesor->givePermissionTo([
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
            'view_myInfo',
            'view_uploadDoc',
            'view_updateDocs',
        ]);

        $directorEscuela->givePermissionTo([
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
            'read_persons',
            'write_persons',
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
        ]);

        $usuario1->assignRole('Administrador');
        $usuario2->assignRole('Administrador');
        $usuario3->assignRole('Profesor');
        $usuario4->assignRole('Director Escuela');
        $usuario5->assignRole('Asistente Administrativo');
        $usuario6->assignRole('Asistente Financiero');

    }
}
