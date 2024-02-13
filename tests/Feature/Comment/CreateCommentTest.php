<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\fake;
use function Pest\Laravel\postJson;

const API_ARTICLES_COMMENTS_CREATE = 'api.articles.comments.create';

it('should successfully create a new comment', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $rawRequestData = rawCommentCreateRequest();

    postJsonAsUser(
        route(API_ARTICLES_COMMENTS_CREATE, [$article]),
        $user,
        ['comment' => $rawRequestData]
    )
        ->assertCreated()
        ->assertJson(['comment' => commentToArray(Comment::firstOrFail(), $user)]);
});

it('should reject creation attempt when a required field is not provided', function ($field, $error) {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $rawRequestData = rawCommentCreateRequest();

    postJsonAsUser(
        route(API_ARTICLES_COMMENTS_CREATE, [$article]),
        $user,
        ['comment' => Arr::except($rawRequestData, $field)]
    )
        ->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('errors.body', $error));
})->with([
    'body' => ['body', 'The comment.body field is required.'],
]);

it('should return an error if no token is provided', function () {
    postJson(route(API_ARTICLES_COMMENTS_CREATE, [fake()->word()]))->assertUnauthorized();
});

it('should return 404 when attempting to create a new comment for non-existing article', function () {
    $user = User::factory()->create();
    $slug = fake()->word();

    postJsonAsUser(
        route(API_ARTICLES_COMMENTS_CREATE, [$slug]),
        $user,
        ['comment' => rawCommentCreateRequest()]
    )->assertNotFound();
});
