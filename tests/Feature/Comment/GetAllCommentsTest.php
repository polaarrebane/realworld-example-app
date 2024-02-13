<?php

use App\Models\Article;
use App\Models\Comment;

use function Pest\Laravel\getJson;

const API_ARTICLES_COMMENTS = 'api.articles.comments.get';

it('it should retrieve all existing comments for a specific article', function () {
    $article = Article::factory()->create();
    $commentsForArticle = Comment::factory()->count(rand(2, 20))->for($article, 'article')->create();
    $remainingComments = Comment::factory()->count(rand(2, 20))->create();

    $response = getJson(route(API_ARTICLES_COMMENTS, [$article]))->assertStatus(200);

    expect($response['comments'])
        ->toContainComments($commentsForArticle)
        ->notToContainComments($remainingComments);
});

it('should return 404 when attempting to retrieve all comments for non-existing article', function () {
    $slug = fake()->word;
    $response = getJson(route(API_ARTICLES_COMMENTS, [$slug]));
    $response->assertNotFound();
});
