<?php

namespace App\Http\Controllers;

use App\Http\RequestData\CreateCommentRequestData;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function index(Article $article): CommentCollection
    {
        return new CommentCollection($article->comments()->get());
    }

    public function store(Article $article, Request $request): CommentResource
    {
        $requestData = CreateCommentRequestData::from($request);

        $comment = new Comment($requestData->all());
        $comment->author()->associate(auth()->user());
        $comment->article()->associate($article);
        $comment->save();

        return new CommentResource($comment);
    }

    public function destroy(Article $article, Comment $comment): Response
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response(status: 200);
    }
}
