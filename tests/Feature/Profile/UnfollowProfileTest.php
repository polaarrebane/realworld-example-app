<?php

use App\Models\User;

use function Pest\Laravel\deleteJson;

const API_PROFILES_UNFOLLOW = 'api.profiles.unfollow';

it('should successfully unfollow user', function () {
    [$user, $following] = User::factory()->count(2)->create();
    $following->followers()->attach($user);

    deleteJsonAsUser(route(API_PROFILES_UNFOLLOW, [$following]), $user)->assertOk();
    expect($user->isFollowing($following))->toBeFalse();
});

it('should return 404 when attempting to unfollow non-existing profile', function () {
    deleteJsonAsUser(route(API_PROFILES_UNFOLLOW, [fake()->word]), User::factory()->create())->assertNotFound();
});

it('should return an error if no token is provided', function () {
    deleteJson(route(API_PROFILES_UNFOLLOW, [fake()->word()]))->assertUnauthorized();
});
