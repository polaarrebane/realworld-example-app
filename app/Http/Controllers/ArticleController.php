<?php

namespace App\Http\Controllers;

use App\Http\RequestData\CreateArticleRequestData;
use App\Http\RequestData\GetAllArticlesRequestData;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index(Request $request): ArticleCollection
    {
        $requestData = GetAllArticlesRequestData::from($request);
        $articles = Article::filtered(
            tag: is_string($requestData->tag) ? $requestData->tag : null,
            author: is_string($requestData->author) ? $requestData->author : null,
            favorited: is_string($requestData->favorited) ? $requestData->favorited : null,
        )
            ->skip(is_int($requestData->offset) ? $requestData->offset : 0)
            ->take(is_int($requestData->limit) ? $requestData->limit : 20)
            ->get();

        return new ArticleCollection($articles);
    }

    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    public function store(Request $request): ArticleResource
    {
        $requestData = CreateArticleRequestData::from($request);

        /** @var User $currentUser */
        $currentUser = auth('api')->user();

        $article = $currentUser->articles()->create($requestData->all());

        $tagIds = collect($requestData->tagList)
            ->transform(fn ($tag) => Tag::firstOrCreate(['value' => $tag])->id); // @phpstan-ignore-line
        $article->tags()->sync($tagIds);

        return new ArticleResource($article);
    }

    public function destroy(Article $article): Response
    {
        $this->authorize('delete', $article);
        $article->delete();

        return response(status: 200);
    }

    public function favorite(Article $article): ArticleResource
    {
        /** @var User $currentUser */
        $currentUser = auth('api')->user();

        $currentUser->favorites()->attach($article);

        return new ArticleResource($article);
    }

    public function unfavorite(Article $article): ArticleResource
    {
        /** @var User $currentUser */
        $currentUser = auth('api')->user();

        $currentUser->favorites()->detach($article);

        return new ArticleResource($article);
    }
}
