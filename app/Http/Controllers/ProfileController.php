<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user): ProfileResource
    {
        return new ProfileResource($user);
    }

    public function follow(User $user): ProfileResource
    {
        $user->followers()->attach(auth()->user());

        return new ProfileResource($user);
    }

    public function unfollow(User $user): ProfileResource
    {
        $user->followers()->detach(auth()->user());

        return new ProfileResource($user);
    }
}
