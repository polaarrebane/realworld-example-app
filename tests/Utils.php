<?php

use App\Models\User;
use Illuminate\Testing\TestResponse;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

//<editor-fold desc="Requests with token">
/**
 * @return array<string, string>
 */
function authHeaderForUser(User $user): array
{
    return ['Authorization' => 'Token '.auth()->fromUser($user)];
}

/**
 * @param  array<string,string>  $headers
 */
function getJsonAsUser(string $route, User $user, array $headers = []): TestResponse
{
    return getJson($route, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function postJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return postJson($route, $data, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function putJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return putJson($route, $data, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function deleteJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return deleteJson($route, $data, $headers + authHeaderForUser($user));
}
//</editor-fold>

//<editor-fold desc="User">
expect()->extend('toBeTokenForUser', function (User $user) {
    /** @phpstan-ignore-next-line */
    auth('api')->forgetUser()->setToken($this->value);

    /** @phpstan-ignore-next-line */
    expect(auth('api')->user()->id)->toBe($user->id);
});
//</editor-fold>
