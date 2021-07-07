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
            'name'         =>'Guillermo Alexander Cornejo Argueta',
            'email'        =>'guillermo.cornejo@ues.edu.sv',
            'password'     => bcrypt(123456789),
            ]);

            //CREACION DE ROLES DEL SISTEMA
            $admin = Role::create(['name' => 'Administrador']);
            
            /* PERMISOS DEL ADMINISTRADOR */
            Permission::create([  'name'=> 'read_escalafones' ]);
            Permission::create([  'name'=> 'write_escalafones' ]);
            
            /* Asignando PERMISOS DEL ADMINISTRADOR */
            $admin->givePermissionTo([
                'read_escalafones',         
                'write_escalafones',
                ]);
            
            $usuario->assignRole('Administrador');

    }

     
}