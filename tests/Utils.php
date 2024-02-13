<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

//<editor-fold desc="Requests with token">
/**
 * @return array<string, string>
 */
function authHeaderForUser(User $user): array
{
    return ['Authorization' => 'Token '.auth()->fromUser($user)];
}

/**
 * @param  array<string,string>  $headers
 */
function getJsonAsUser(string $route, User $user, array $headers = []): TestResponse
{
    return getJson($route, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function postJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return postJson($route, $data, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function putJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return putJson($route, $data, $headers + authHeaderForUser($user));
}

/**
 * @param  mixed[]  $data
 * @param  array<string,string>  $headers
 */
function deleteJsonAsUser(string $route, User $user, array $data = [], array $headers = []): TestResponse
{
    return deleteJson($route, $data, $headers + authHeaderForUser($user));
}

//</editor-fold>

//<editor-fold desc="User">
expect()->extend('toBeTokenForUser', function (User $user) {
    /** @phpstan-ignore-next-line */
    auth('api')->forgetUser()->setToken($this->value);

    /** @phpstan-ignore-next-line */
    expect(auth('api')->user()->id)->toBe($user->id);
});
//</editor-fold>

//<editor-fold desc="Article">
/**
 * @return array<string, string|bool|int|array<string,string|bool>>
 */
function articleToArray(Article $article, ?User $author = null): array
{
    $author = $author ?? $article->author;

    return [
        'slug' => $article->slug,
        'title' => $article->title,
        'description' => $article->description,
        'body' => $article->body,
        'tagList' => $article->tags()->pluck('value')->sort()->values()->toArray(),
        'createdAt' => (string) $article->created_at->toISOString(),
        'updatedAt' => (string) $article->updated_at->toISOString(),
        'favorited' => auth()->user()?->isFavorited($article) ?? false,
        'favoritesCount' => $article->favoritesCount,
        'author' => [
            'username' => $author->username,
            'bio' => $author->bio,
            'image' => $author->image,
            'following' => (auth()->user()?->isFollowing($author) ?? false),
        ],
    ];
}

expect()->extend('toContainArticles', function (Collection $articles) {
    $articles
        ->transform(fn (Article $article) => articleToArray($article))
        ->each(
            fn (array $article) => expect($this->value)->toContain($article)
        );

    return $this;
});

expect()->extend('notToContainArticles', function (Collection $articles) {
    $articles
        ->transform(fn (Article $article) => articleToArray($article))
        ->each(
            fn (array $article) => expect($this->value)->not()->toContain($article)
        );

    return $this;
});

expect()->extend('toContainFeedForUser', function (User $currentUser, ?int $offset = null, ?int $limit = null) {
    $articleArray = Article::feed($currentUser)
        ->skip($offset ?? 0)
        ->take($limit ?? 20)
        ->get()
        ->transform(fn (Article $article) => articleToArray($article)); // @phpstan-ignore-line
    $articleArray
        ->each(fn (array $article) => expect($this->value)->toContain($article)); // @phpstan-ignore-line

    return $this;
});
//</editor-fold>
