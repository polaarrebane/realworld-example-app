<?php

namespace App\Http\Controllers;

use App\Http\RequestData\LoginUserRequestData;
use App\Http\RequestData\RegisterUserRequestData;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request): UserResource
    {
        $requestData = RegisterUserRequestData::from($request);

        $user = new User($requestData->all());
        $user->password = $requestData->password;
        $user->save();

        auth('api')->login($user);
        auth('api')->refresh();

        return new UserResource($user);
    }

    /**
     * @throws AuthenticationException
     */
    public function login(Request $request): UserResource
    {
        $requestData = LoginUserRequestData::from($request);

        if (! $token = auth()->attempt($requestData->all())) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return new UserResource(auth('api')->user());
    }

    public function get(): UserResource
    {
        return new UserResource(auth('api')->user());
    }
}
