<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class GetFeedRequestData extends Data
{
    public function __construct(
        public readonly int|Optional $offset,
        public readonly int|Optional $limit,
    ) {
    }

    /**
     * @return array<string, string[]>
     */
    public static function rules(): array
    {
        return [
            'tag' => ['sometimes', 'required', 'string', 'max:255'],
            'author' => ['sometimes', 'required', 'string', 'max:255'],
            'favorited' => ['sometimes', 'required', 'string', 'max:255'],
            'offset' => ['sometimes', 'required', 'integer', 'gte:0'],
            'limit' => ['sometimes', 'required', 'integer', 'gt:0'],
        ];
    }
}
