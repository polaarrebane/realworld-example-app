<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\putJson;

const API_ARTICLES_UPDATE = 'api.articles.update';

it('should successfully update an article', function () {
    /** @var Article $article */
    $article = Article::factory()->create();
    $rawRequestData = rawArticleUpdateRequest();

    $response = putJsonAsUser(
        route(API_ARTICLES_UPDATE, [$article]),
        $article->author,
        ['article' => $rawRequestData]
    );

    $article->refresh();

    $response->assertOk()->assertJson(['article' => articleToArray($article)]);
});

it('it should reject update attempt when non-author user tries to update', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    putJsonAsUser(
        route(API_ARTICLES_UPDATE, [$article]),
        $user,
        ['article' => rawArticleUpdateRequest()]
    )->assertStatus(403);
});

it('should return an error if no token is provided', function () {
    $article = Article::factory()->create();
    putJson(route(API_ARTICLES_UPDATE, [$article]))->assertUnauthorized();
});

it('should return an error if no data is provided', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    putJsonAsUser(
        route(API_ARTICLES_UPDATE, [$article]),
        $user,
        ['article' => []]
    )->assertStatus(422);
});

/**
 * @return array<string,string>
 */
function rawArticleUpdateRequest(): array
{
    /** @var string $body */
    $body = fake()->paragraphs(nb: 5, asText: true);

    return [
        'title' => fake()->text(),
        'description' => fake()->text(),
        'body' => $body,
    ];
}
