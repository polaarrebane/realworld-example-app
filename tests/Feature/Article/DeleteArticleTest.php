<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\delete;

const API_ARTICLES_DELETE = 'api.articles.delete';

it('should successfully delete an article', function () {
    $article = Article::factory()->create();

    deleteJsonAsUser(
        route(API_ARTICLES_DELETE, [$article]),
        $article->author,
    )->assertOk();

    expect(Article::find($article->id))->toBeNull();
});

it('should reject delete attempt when user is not an author', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    deleteJsonAsUser(
        route(API_ARTICLES_DELETE, [$article]),
        $user,
    )->assertStatus(403);
});

it('should return an error if no token is provided', function () {
    delete(route(API_ARTICLES_DELETE, [Article::factory()->create()]))->assertUnauthorized();
});
