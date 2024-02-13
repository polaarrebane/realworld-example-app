<?php

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\fake;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

const API_USERS_CREATE = 'api.users.create';
const USERS_TABLE = 'users';

it('should successfully register a new user', function () {
    $rawUserData = rawUserCreateRequest();

    assertDatabaseMissing(USERS_TABLE, Arr::except($rawUserData, 'password'));

    postJson(route(API_USERS_CREATE), ['user' => $rawUserData])
        ->assertCreated()
        ->assertJson(['user' => Arr::except($rawUserData, 'password')])
        ->assertJson(fn (AssertableJson $json) => $json->whereType('user.token', 'string'));

    assertDatabaseHas(USERS_TABLE, Arr::except($rawUserData, 'password'));
});

it('should not allow registration of a user with the same email or username', function () {
    $rawUserData = User::factory()->create()->toArray();
    Arr::set($rawUserData, 'password', fake()->password());

    postJson(route(API_USERS_CREATE), ['user' => $rawUserData])
        ->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) => $json->where(
                'errors.body',
                'The user.username has already been taken. The user.email has already been taken.'
            )
        );
});

it('should reject registration attempt when a required field is not provided', function ($field, $error) {
    $rawUserData = rawUserCreateRequest();

    postJson(route(API_USERS_CREATE), ['user' => Arr::except($rawUserData, $field)])
        ->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('errors.body', $error));

    assertDatabaseMissing(USERS_TABLE, Arr::except($rawUserData, 'password'));
})->with([
    'email' => ['email', 'The user.email field is required.'],
    'username' => ['username', 'The user.username field is required.'],
    'password' => ['password', 'The user.password field is required.'],
]);

/**
 * @return array<string,string>
 */
function rawUserCreateRequest(): array
{
    return [
        'username' => fake()->word(),
        'email' => fake()->email(),
        'password' => fake()->password(),
    ];
}
