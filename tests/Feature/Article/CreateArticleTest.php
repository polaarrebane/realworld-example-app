<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\fake;
use function Pest\Laravel\postJson;

const API_ARTICLES_CREATE = 'api.articles.create';

it('should successfully create a new article', function () {
    $user = User::factory()->create();
    $rawRequestData = rawArticleCreateRequest();

    postJsonAsUser(
        route(API_ARTICLES_CREATE),
        $user,
        ['article' => $rawRequestData]
    )
        ->assertCreated()
        ->assertJson(['article' => articleToArray(Article::firstOrFail(), $user)]);
});

it('should reject creation attempt when a required field is not provided', function ($field, $error) {
    $user = User::factory()->create();
    $rawRequestData = rawArticleCreateRequest();

    postJsonAsUser(
        route(API_ARTICLES_CREATE),
        $user,
        ['article' => Arr::except($rawRequestData, $field)]
    )
        ->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('errors.body', $error));
})->with([
    'title' => ['title', 'The article.title field is required.'],
    'description' => ['description', 'The article.description field is required.'],
    'body' => ['body', 'The article.body field is required.'],
    'tagList' => ['tagList', 'The article.tag list field is required.'],
]);

it('should return an error if no token is provided', function () {
    postJson(route(API_ARTICLES_CREATE))->assertUnauthorized();
});

/**
 * @return array<string,string|string[]>
 */
function rawArticleCreateRequest(): array
{
    return [
        'title' => fake()->text(),
        'description' => fake()->text(),
        'body' => fake()->paragraphs(nb: 5, asText: true),
        'tagList' => fake()->words(7),
    ];
}
