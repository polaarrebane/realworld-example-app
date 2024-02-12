<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\getJson;

const API_ARTICLES_GET_FEED = 'api.articles.feed';

it("should successfully retrieve a user's feed", function () {
    $currentUser = User::factory()->create();
    createFeedForUser($currentUser);

    $response = getJsonAsUser(
        route(API_ARTICLES_GET_FEED),
        $currentUser
    );

    expect($response->getStatusCode())->toBe(200)
        ->and($response['articles'])->toContainFeedForUser($currentUser)
        ->and($response['articlesCount'])->toBe(Article::feed($currentUser)->count());
});

it('should retrieve feed with specified offset and limit', function () {
    $currentUser = User::factory()->create();
    createFeedForUser($currentUser, 10);

    $limit = 5;
    $offset = 10;

    $response = getJsonAsUser(
        route(API_ARTICLES_GET_FEED, [
            'limit' => $limit,
            'offset' => $offset,
        ]),
        $currentUser
    );

    expect($response->getStatusCode())->toBe(200)
        ->and($response['articles'])->toContainFeedForUser($currentUser, $offset, $limit)
        ->and($response['articlesCount'])->toBe(Article::feed($currentUser)->skip($offset)->take($limit)->count());
});

it('should return an error if no token is provided', function () {
    getJson(route(API_ARTICLES_GET_FEED))->assertUnauthorized();
});

function createFeedForUser(User $currentUser, ?int $count = null): void
{
    $users = collect(User::factory()->count(5)->create());
    $users->each(function (User $user) use ($count) {
        Article::factory()->for($user, 'author')->count($count ?? rand(1, 3))->create();
    });
    $currentUser->following()->sync($users->pluck('id'));
}
