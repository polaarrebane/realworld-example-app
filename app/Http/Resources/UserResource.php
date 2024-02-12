<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';

    /**
     * @return array<string,string>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'email' => $this->resource->email,
            'username' => $this->resource->username,
            'bio' => $this->resource->bio,
            'image' => $this->resource->image,
        ];

        if (auth('api')->user()) {
            $result += ['token' => auth('api')->getToken()->get()];
        }

        return $result;
    }
}
