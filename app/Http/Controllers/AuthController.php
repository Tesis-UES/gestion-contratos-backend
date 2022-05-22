<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneratorTrait;
use App\Http\Traits\WorklogTrait;
use App\Mail\NewUserNotification;
use App\Models\School;
use App\Models\User;
use App\Models\worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Mail;


class AuthController extends Controller
{
    use GeneratorTrait, WorklogTrait;

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
            return response(['message' => 'Credenciales incorrectas'], 401);
        }
        if ($user->password_expiration != null && $user->password_expiration > new \DateTime()) {
            return response([
                'message' => 'Su contraseña ha expirado, pongase en contacto con el administrador del sistema',
            ], 401);
        }

        $user->getPermissionsViaRoles();
        $token = $user->createToken('myappToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        if ($user['school_id']) {
            $school = School::findorFail($user['school_id']);
            $response = array_merge($response, ['school' => $school]);
        }

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
        if ($request->query('relevance')) {
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
            'role'      => 'required|string',
            'school_id' => 'numeric',
        ]);

        $expirationDate = (new \DateTime())->add(new \DateInterval(env('PASSWORD_VALID_FOR', 'P7D')));
        $newPassword = $this->generatePassword(32);

        switch ($request->role) {
            case 'Administrador':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como administrador', "medium");
                return response($response, 201);
                break;

            case 'Candidato':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'school_id'             => $fields['school_id'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Profesor', "medium");
                return response($response, 201);
                break;

            case 'Director Escuela':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'school_id'             => $fields['school_id'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Director de Escuela', "medium");
                return response($response, 201);
                break;

            case 'Asistente Administrativo':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Asistente Administrativo', "medium");
                return response($response, 201);

                break;

            case 'Asistente Financiero':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Asistente Financiero', "medium");
                return response($response, 201);

                break;

            case 'Recursos Humanos':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Recursos Humanos', "medium");
                return response($response, 201);

                break;

            case 'Decano':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Decano', "medium");
                return response($response, 201);

                break;

            case 'Super Administrador':
                $user = User::create([
                    'name'                  => $fields['name'],
                    'email'                 => $fields['email'],
                    'password'              => bcrypt($newPassword),
                    'password_expiration'   => $expirationDate,
                ]);
                $user->assignRole($request->role);
                try {
                    Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "Si se envio el correo electronico",
                    ];
                } catch (\Swift_TransportException $e) {
                    $response = [
                        'user'      => $user,
                        'mensaje'   => "No se ha enviado el correo electronico",
                    ];
                }

                $this->RegisterAction('El administrador ha registrado al usuario ' . $user->name . ' como Super Administrador', "medium");
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
        $user->password_expiration = null;
        $user->save();
        $this->RegisterAction('El usuario ' . $user->name . ' ha cambiado su contraseña.');
        return response(['message' => "Se ha actualizado la contraseña con exito"], 200);
    }

    public function changeUserPassword($userId, Request $request)
    {
        $user = User::findOrFail($userId);

        $newPassword = $this->generatePassword(32);
        $expirationDate = (new \DateTime())->add(new \DateInterval(env('PASSWORD_VALID_FOR', 'P7D')));

        $user->password = bcrypt($newPassword);
        $user->password_expiration = $expirationDate;
        $user->save();
        try {
            Mail::to($user->email)->send(new NewUserNotification($user->email, $newPassword));
            $this->RegisterAction('El administrador ha cambiado la contraseña del usuario con id' . $user->id);
            return response(['message' => "Se ha actualizado la contraseña con exito y se ha enviado el correo"], 200);
        } catch (\Swift_TransportException $e) {
            return response(['message' => "No se ha cambiado la contraseña y enviado el correo, por favor intente denuevo o contacte con el administrador"], 200);
        }
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
                $this->RegisterAction('El administrador ha actualizado al usuario ' . $user->name . ' como administrador', "medium");
                return response($response, 201);
                break;

            case 'Candidato':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                    'school_id' => $fields['school_id'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Profesor', "medium");
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
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Director de Escuela', "medium");
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
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Asistente Administrativo', "medium");
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
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Asistente Financiero', "medium");
                return response($response, 201);

                break;
            case 'Recursos Humanos':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Recursos Humanos', "medium");
                return response($response, 201);

                break;
            case 'Decano':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Decano', "medium");
                return response($response, 201);

                break;
            case 'Super Administrador':
                $user->update([
                    'name'      => $fields['name'],
                    'email'     => $fields['email'],
                ]);
                $user->syncRoles($request->role);
                $response = [
                    'user' => $user,
                ];
                $this->RegisterAction('El administrador ha Actualizado al usuario ' . $user->name . ' como Super Administrador', "medium");
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
