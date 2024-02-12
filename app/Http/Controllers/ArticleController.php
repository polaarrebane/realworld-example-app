<?php

namespace App\Http\Controllers;

use App\Http\RequestData\CreateArticleRequestData;
use App\Http\Resources\ArticleResource;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function store(Request $request): ArticleResource
    {
        $requestData = CreateArticleRequestData::from($request);

        /** @var User $currentUser */
        $currentUser = auth('api')->user();

        $article = $currentUser->articles()->create($requestData->all());

        $tagIds = collect($requestData->tagList)
            ->transform(fn ($tag) => Tag::firstOrCreate(['value' => $tag])->id); /** @phpstan-ignore-line  */
        $article->tags()->sync($tagIds);

        return new ArticleResource($article);
    }
}
