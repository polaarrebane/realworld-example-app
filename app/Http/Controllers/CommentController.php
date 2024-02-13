<?php

namespace App\Http\Controllers;

use App\Http\RequestData\CreateCommentRequestData;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Article $article, Request $request): CommentResource
    {
        $requestData = CreateCommentRequestData::from($request);

        $comment = new Comment($requestData->all());
        $comment->author()->associate(auth()->user());
        $comment->article()->associate($article);
        $comment->save();

        return new CommentResource($comment);
    }
}
