<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\postJson;

const API_ARTICLES_UNFAVORITE = 'api.articles.unfavorite';

it('should successfully unfavorite a previously favorited article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $article->favorited()->attach($user);
    $response = deleteJsonAsUser(route(API_ARTICLES_UNFAVORITE, [$article]), $user);

    expect($response->getStatusCode())->toBe(200)
        ->and($user->isFavorited($article))->toBeFalse();
});

it('should return 404 when attempting to unfavorite non-existing article', function () {
    deleteJsonAsUser(route(API_ARTICLES_UNFAVORITE, [fake()->word]), User::factory()->create())->assertNotFound();
});

it('should return an error if no token is provided', function () {
    postJson(route(API_ARTICLES_UNFAVORITE, [fake()->word()]))->assertUnauthorized();
});
