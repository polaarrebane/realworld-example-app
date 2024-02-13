<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\deleteJson;

const API_ARTICLES_COMMENTS_DELETE = 'api.articles.comments.delete';

it('should successfully delete their own comment', function () {
    $comment = Comment::factory()->create();
    deleteJsonAsUser(
        route(API_ARTICLES_COMMENTS_DELETE, [$comment->article, $comment]),
        $comment->author
    )->assertStatus(200);
});

it("should not delete someone else's comment", function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create();
    deleteJsonAsUser(
        route(API_ARTICLES_COMMENTS_DELETE, [$comment->article, $comment]),
        $user
    )->assertForbidden();
});

it('should return an error if no token is provided', function () {
    deleteJson(
        route(API_ARTICLES_COMMENTS_DELETE, [fake()->word(), fake()->randomNumber()])
    )->assertUnauthorized();
});

it('should return 404 when attempting to delete a comment for non-existing article', function () {
    $user = User::factory()->create();
    $slug = fake()->word();

    deleteJsonAsUser(
        route(API_ARTICLES_COMMENTS_DELETE, [fake()->word(), fake()->randomNumber()]),
        $user
    )->assertNotFound();
});
