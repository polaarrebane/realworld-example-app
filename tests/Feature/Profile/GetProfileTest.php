<?php

use App\Models\User;

use function Pest\Laravel\getJson;

const API_PROFILES_GET = 'api.profiles.get';

it('should retrieve profile information when no token is provided', function () {
    $user = User::factory()->create();

    getJson(route(API_PROFILES_GET, [$user]))
        ->assertOk()->assertJson([
            'profile' => [
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
                'following' => false,
            ],
        ]);
});

it('should retrieve profile information for a profile that the user is subscribed to', function () {
    [$user, $following] = User::factory()->count(2)->create();
    $user->following()->attach($following);

    getJsonAsUser(route(API_PROFILES_GET, [$following]), $user)
        ->assertOk()->assertJson([
            'profile' => [
                'username' => $following->username,
                'bio' => $following->bio,
                'image' => $following->image,
                'following' => true,
            ],
        ]);
});

it('should retrieve profile information for a profile that the user is not subscribed to', function () {
    [$user, $unsubscribed] = User::factory()->count(2)->create();

    getJsonAsUser(route(API_PROFILES_GET, [$unsubscribed]), $user)
        ->assertOk()->assertJson([
            'profile' => [
                'username' => $unsubscribed->username,
                'bio' => $unsubscribed->bio,
                'image' => $unsubscribed->image,
                'following' => false,
            ],
        ]);
});

it('should return 404 when attempting to retrieve information for non-existing profile', function () {
    getJson(route(API_PROFILES_GET, [fake()->word]))->assertNotFound();
});
