<?php

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\getJson;

const API_ARTICLES_GET = 'api.articles.get';

it('should retrieve all articles', function () {
    Article::factory()->count(20)->create();

    $response = getJson(route(API_ARTICLES_GET));

    expect($response->getStatusCode())->toBe(200)
        ->and($response['articles'])->toContainArticles(Article::all())
        ->and($response['articlesCount'])->toBe(Article::count());
});

it('should retrieve articles with specified offset and limit', function () {
    $offset = 10;
    $limit = 5;

    Article::factory()->count(50)->create();

    $response = getJson(route(API_ARTICLES_GET, [
        'limit' => $limit,
        'offset' => $offset,
    ]));

    $articles = Article::skip($offset)->take($limit);

    expect($response->getStatusCode())->toBe(200)
        ->and($response['articles'])->toContainArticles($articles)
        ->and($response['articlesCount'])->toBe($articles->count());
});

it('should retrieve filtered articles', function () {
    $allArticlesCount = 20;
    $favoritedArticlesCount = 10;
    $favoritedAndTaggedArticlesCount = 5;

    [$author, $favorited] = User::factory()->count(2)->create();

    $allArticles = Article::factory()
        ->count($allArticlesCount)
        ->for($author, 'author')
        ->create();

    $favoritedArticles = $allArticles
        ->take($favoritedArticlesCount)
        ->each(fn (Article $article) => $article->favorited()->attach($favorited));

    $tag = Tag::factory()->create();

    $favoritedAndTaggedArticles = $favoritedArticles
        ->take($favoritedAndTaggedArticlesCount)
        ->each(fn (Article $article) => $article->tags()->attach($tag));

    $remainingArticlesCount = $allArticles->count() - $favoritedAndTaggedArticles->count();
    $remainingArticles = $allArticles->take(-$remainingArticlesCount);

    $response = getJson(route(API_ARTICLES_GET, [
        'author' => $author->username,
        'favorited' => $favorited->username,
        'tag' => $tag->value,
    ]));

    expect($response->getStatusCode())->toBe(200)
        ->and($response['articles'])->toContainArticles($favoritedAndTaggedArticles)
        ->and($response['articles'])->notToContainArticles($remainingArticles)
        ->and($response['articlesCount'])->toBe($favoritedAndTaggedArticles->count());
});
