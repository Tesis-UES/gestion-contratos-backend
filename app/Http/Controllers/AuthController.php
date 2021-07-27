<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Traits\WorklogTrait;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use WorklogTrait;
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $user->getPermissionsViaRoles();
        $token = $user->createToken('myappToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'],)->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(
                [
                    'message' => 'bad credentials'
                ],
                401
            );
        }

        $user->getPermissionsViaRoles();
        $token = $user->createToken('myappToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $this->RegisterAction('El usuario ' . $request->user()->name . ' ha cerrado sesión en el sistema.');
        return response([
            'message' => 'logged out'
        ], 200);
    }

    public function worklog(Request $request)
    {
        $worklog  = worklog::where('username', 'LIKE', '%' . $request->query('name') . '%');
        if ($request->query('date_after')) {
            $dayafter = new \DateTime($request->query('date_after'));
            $worklog = $worklog->whereDate('created_at', '>=', $dayafter);
        }
        if ($request->query('date_before')) {
            $daybefore = new \DateTime($request->query('date_before'));
            $worklog = $worklog->whereDate('created_at', '<=', $daybefore);
        }
        if($request->query('relevance')) {
            $relevance = $request->query('relevance');
            $worklog = $worklog->whereIn('relevance', explode(',', $relevance));
        }

        $worklog = $worklog->orderBy('created_at', $request->query('order', 'desc'))->paginate(15);

        $response = [
            'worklog' => $worklog,
        ];

        return response($response, 200);
    }

    public function AllRoles()
    {
        $systemRoles = Role::all();
        $response = [
            'systemRoles' => $systemRoles,
        ];
        $this->RegisterAction('El usuario ha consultado el catalogo de roles');
        return response($response, 200);
    }

    public function AllUsers()
    {
        $users = User::with('roles', 'school')->get();
        $this->RegisterAction('El usuario ha consultado el listado de usuarios');
        return response($users, 200);
    }

    public function getUser($id)
    {
        $user = User::with('roles', 'school')->findOrFail($id);
        $this->RegisterAction('El usuario ha consultado la informacion del usuario ' . $id);
        return response($user, 200);
    }


    public function getPermissions()
    {
        $usuario =  Auth::user();
        $permisos = $usuario->getAllPermissions();
        $permissions = [];
        foreach ($permisos as $permission) {
            $permissions[] = $permission->name;
        }
        return $permissions;
    }

    public function createUser(Request $request)
    {

        $fields = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|string',
            'role'      => 'required|string',
            'school_id' => 'numeric',
        ]);

        switch ($request->role) {
            case 'Administrador':
                $user = User::create([
                    'name' => $fields['name'],
                    'email' => $fields['email'],
                    'password' => bcrypt($fields['password']),
                ]);
                $user->assignRole($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como administrador.');
                return response($response, 201);
                break;

            case 'Profesor':
                $user = User::create([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'school_id' => $fields['school_id'],
                    'password' => bcrypt($fields['password']),
                ]);
                $user->assignRole($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Profesor.');
                return response($response, 201);
                break;

            case 'Director Escuela':
                $user = User::create([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'school_id' => $fields['school_id'],
                    'password' => bcrypt($fields['password']),
                ]);
                $user->assignRole($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Director de Escuela');
                return response($response, 201);
                break;

            case 'Asistente Administrativo':
                $user = User::create([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'password' => bcrypt($fields['password']),
                ]);
                $user->assignRole($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Asistente Administrativo');
                return response($response, 201);

                break;

            case 'Asistente Financiero':
                $user = User::create([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'password' => bcrypt($fields['password']),
                ]);
                $user->assignRole($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Asistente Financiero');
                return response($response, 201);

                break;

            default:
                return response(
                    [
                        'message' => 'El rol que esta ingresando No Existe',
                    ],
                    400
                );
                break;
        }
    }

    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'password'  => 'required|string|confirmed',
        ]);

        $user = Auth::user();
        if (Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'La nueva contraseña debe de ser diferente a la actual.'], 401);
        }
        $user->password =  bcrypt($fields['password']);
        $user->save();
        $this->RegisterAction('El usuario ' . $user->name . ' ha cambiado su contraseña.');
        return response(['message' => "Se ha actualizado la contraseña con exito"], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $fields = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|string',
            'role'      => 'required|string',
            'school_id' => 'numeric',
        ]);

        $user = User::findOrFail($id);
        switch ($request->role) {
            case 'Administrador':
                $user->update([
                    'name' => $fields['name'],
                    'email' => $fields['email'],
                ]);

                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha actualizado al usuario ' . $user->name . ' como administrador.');
                return response($response, 201);
                break;

            case 'Profesor':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'school_id' => $fields['school_id'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Profesor.');
                return response($response, 201);
                break;

            case 'Director Escuela':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'school_id' => $fields['school_id'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Director de Escuela');
                return response($response, 201);
                break;

            case 'Asistente Administrativo':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Asistente Administrativo');
                return response($response, 201);

                break;

            case 'Asistente Financiero':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Asistente Financiero');
                return response($response, 201);

                break;

            default:
                return response(
                    [
                        'message' => 'El rol que esta ingresando No Existe',
                    ],
                    400
                );
                break;
        }
    }
}
