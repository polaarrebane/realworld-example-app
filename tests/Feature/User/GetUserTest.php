<?php

use App\Models\User;

use function Pest\Laravel\getJson;

const API_USER_GET = 'api.user.get';

it('should retrieve user information when a valid token is provided', function () {
    $user = User::factory()->create();
    $response = getJsonAsUser(route(API_USER_GET), $user);
    $response->assertOk()->assertJson(['user' => $user->toArray()]);
    expect($response['user']['token'] ?? null)->toBeTokenForUser($user);
});

it('should return an error if no token is provided', function () {
    $response = getJson(route(API_USER_GET));
    $response->assertUnauthorized();
});
