<?php

use App\Models\Article;

use function Pest\Laravel\getJson;

const API_ARTICLES_GET_BY_SLUG = 'api.articles.slug';

it('should successfully retrieve an existing article', function () {
    $article = Article::factory()->create();
    $slug = $article->slug;
    $response = getJson(route(API_ARTICLES_GET_BY_SLUG, [$slug]));
    $response->assertOk()->assertJson(['article' => articleToArray($article)]);
});

it('should return 404 when attempting to retrieve non-existing article', function () {
    $slug = fake()->word;
    $response = getJson(route(API_ARTICLES_GET_BY_SLUG, [$slug]));
    $response->assertNotFound();
});
