<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\worklog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
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

        return response([
            'message' => 'logged out'
        ], 200);
    }

    public function worklog()
    {
        $worklog  = worklog::orderBy('created_at', 'desc')->paginate(15);
        $response = [
            'worklog' => $worklog,
        ];

        return response($response, 200);
    }

    public function AllRoles(){
        $sismtemRoles = Role::all();
        $response = [
            'sismtemRoles' => $sismtemRoles,
        ];

        return response($response, 200);

    }
}
