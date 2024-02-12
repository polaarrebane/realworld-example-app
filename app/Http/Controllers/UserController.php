<?php

namespace App\Http\Controllers;

use App\Http\RequestData\RegisterUserRequestData;
use App\Http\Resources\UserResource;
use App\Models\User;
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
}
