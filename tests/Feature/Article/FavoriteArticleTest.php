<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\postJson;

const API_ARTICLES_FAVORITE = 'api.articles.favorite';

it('should successfully favorite an article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $response = postJsonAsUser(route(API_ARTICLES_FAVORITE, [$article]), $user);

    expect($response->getStatusCode())->toBe(200)
        ->and($user->isFavorited($article))->toBeTrue();
});

it('should return 404 when attempting to favorite a non-existing article', function () {
    postJsonAsUser(route(API_ARTICLES_FAVORITE, [fake()->word]), User::factory()->create())->assertNotFound();
});

it('should return an error if no token is provided', function () {
    postJson(route(API_ARTICLES_FAVORITE, [fake()->word()]))->assertUnauthorized();
});
