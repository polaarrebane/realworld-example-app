<?php

use App\Models\User;

use function Pest\Laravel\postJson;

const API_PROFILES_FOLLOW = 'api.profiles.follow';

it('should successfully follow user', function () {
    [$user, $following] = User::factory()->count(2)->create();

    postJsonAsUser(route(API_PROFILES_FOLLOW, [$following]), $user)->assertOk();
    expect($user->isFollowing($following))->toBeTrue();
});

it('should return 404 when attempting to follow non-existing profile', function () {
    postJsonAsUser(route(API_PROFILES_FOLLOW, [fake()->word]), User::factory()->create())->assertNotFound();
});

it('should return an error if no token is provided', function () {
    postJson(route(API_PROFILES_FOLLOW, [fake()->word()]))->assertUnauthorized();
});
