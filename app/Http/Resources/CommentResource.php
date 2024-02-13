<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public static $wrap = 'comment';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'createdAt' => $this->resource->created_at->toISOString(),
            'updatedAt' => $this->resource->updated_at->toISOString(),
            'body' => $this->resource->body,
            'author' => [
                'username' => $this->resource->author->username,
                'bio' => $this->resource->author->bio,
                'image' => $this->resource->author->image,
                'following' => auth()->user()?->isFollowing($this->resource->author) ?? false,
            ],
        ];
    }
}
