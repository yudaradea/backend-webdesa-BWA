<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginStoreRequest;
use App\Interfaces\AuthRepositoryInterfaces;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthRepositoryInterfaces $authRepository;

    public function __construct(AuthRepositoryInterfaces $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginStoreRequest $request)
    {
        $request = $request->validated();

        return $this->authRepository->login($request);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function me()
    {
        return $this->authRepository->me();
    }
}
