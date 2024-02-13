<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public static $wrap = 'profile';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'username' => $this->resource->username,
            'bio' => $this->resource->bio,
            'image' => $this->resource->image,
        ];

        $result += ['following' => auth()->user()?->isFollowing($this->resource) ?? false];

        return $result;
    }
}
