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
        $usuario = User::create([
            'name'      => 'Admin',
            'email'     => 'amin@ues.edu.sv',
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
            'read_persons',
            'write_persons',
            'write_users',
            'read_users',
        ]);

        $profesor->givePermissionTo([
            'read_escalafones',
            'write_faculties',
            'read_activities',
            'write_activities',
            'read_courses',
            'read_contractTypes',
            'read_persons',
        ]);

        $directorEscuela->givePermissionTo([
            'read_escalafones',
            'read_faculties',
            'write_faculties',
            'read_schools',
            'write_schools',
            'read_activities',
            'write_activities',
            'read_courses',
            'write_courses',
            'read_contractTypes',
            'read_persons',
            'write_persons',
        ]);

        $asistenteAdmin->givePermissionTo([
            'read_worklog',
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
        ]);

        $usuario->assignRole('Administrador');
    }
}
