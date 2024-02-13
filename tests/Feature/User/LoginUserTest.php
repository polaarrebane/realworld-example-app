<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\fake;
use function Pest\Laravel\postJson;

const API_USERS_LOGIN = 'api.users.login';

it('should allow an existing registered user to log in', function () {
    $rawUserLoginRequest = rawUserLoginRequest();
    $user = User::factory()->create($rawUserLoginRequest);

    $response = postJson(route(API_USERS_LOGIN), ['user' => $rawUserLoginRequest]);
    $response->assertOk()->assertJson(['user' => $user->toArray()]);

    expect($response['user']['token'])->toBeTokenForUser($user);
});

it('should reject login attempt with invalid credentials', function () {
    postJson(
        uri: route(API_USERS_LOGIN),
        data: ['user' => rawUserLoginRequest()]
    )->assertUnauthorized();
});

it('should reject login attempt when no credentials are provided', function () {
    postJson(route(API_USERS_LOGIN))
        ->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) => $json->where(
                'errors.body',
                'The user.email field is required. The user.password field is required.'
            )
        );
});

/**
 * @return array<string,string>
 */
function rawUserLoginRequest(): array
{
    return [
        'email' => fake()->email(),
        'password' => fake()->password(),
    ];
}
