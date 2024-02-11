<?php

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\putJson;

const API_USER_UPDATE = 'api.user.update';

it('should update user profile information when a valid token and valid data are provided', function () {
    $rawUserRequest = rawUserUpdateRequest();
    $user = User::factory()->create();

    putJsonAsUser(
        route(API_USER_UPDATE),
        $user,
        ['user' => $rawUserRequest]
    )
        ->assertOk()
        ->assertJson([
            'user' => Arr::except($rawUserRequest, 'password'),
        ]);

    $user->refresh();

    expect($user->username)->toBe($rawUserRequest['username'])
        ->and($user->email)->toBe($rawUserRequest['email'])
        ->and($user->bio)->toBe($rawUserRequest['bio'])
        ->and($user->image)->toBe($rawUserRequest['image'])
        ->and(Hash::check($rawUserRequest['password'], $user->password))->toBeTrue();
});

it('should return an error if no token is provided', function () {
    putJson(route(API_USER_UPDATE))->assertUnauthorized();
});

it('should return an error if no data is provided', function () {
    $user = User::factory()->create();

    putJsonAsUser(
        route(API_USER_UPDATE),
        $user,
        ['user' => []]
    )->assertStatus(422);
});

it('it should return an error if the email is already associated with an existing account', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    putJsonAsUser(
        route(API_USER_UPDATE),
        $user,
        ['user' => ['email' => $user2->email]]
    )
        ->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) => $json->where(
                'errors.body',
                'The user.email has already been taken.'
            )
        );
});

it('it should return an error if the username is already associated with an existing account', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    putJsonAsUser(
        route(API_USER_UPDATE),
        $user,
        ['user' => ['username' => $user2->username]]
    )
        ->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) => $json->where(
                'errors.body',
                'The user.username has already been taken.'
            )
        );
});

/**
 * @return array<string,string>
 */
function rawUserUpdateRequest(): array
{
    /** @var string $bio */
    $bio = fake()->paragraphs(nb: 5, asText: true);

    return [
        'username' => fake()->word(),
        'email' => fake()->email(),
        'password' => fake()->password(),
        'bio' => $bio,
        'image' => fake()->imageUrl(),
    ];
}
