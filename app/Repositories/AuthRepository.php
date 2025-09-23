<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterfaces;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterfaces
{
    public function login(array $data)
    {
        if (!Auth::guard('web')->attempt($data)) {
            return response([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;;

        return response([
            'success' => true,
            'message' => 'Login Berhasil',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        $response = [
            'success' => true,
            'message' => 'Logout Berhasil',
        ];

        return response($response, 200);
    }

    public function me()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $user->load('roles.permissions');

            $permissions = $user->roles->flatMap->permissions->pluck('name');

            $role = $user->roles->first()->name;

            return response()->json([
                'message' => 'User data',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'permissions' => $permissions,
                    'role' => $role
                ],
            ]);
        }

        return response([
            'success' => false,
            'message' => 'You are not logged in'
        ], 401);
    }
}
