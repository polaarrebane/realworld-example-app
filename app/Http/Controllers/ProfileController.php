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
}
