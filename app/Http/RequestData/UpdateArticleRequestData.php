<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateArticleRequestData extends Data
{
    public function __construct(
        #[MapInputName('article.title')]
        public readonly string|Optional $title,

        #[MapInputName('article.description')]
        public readonly string|Optional $description,

        #[MapInputName('article.body')]
        public readonly string|Optional $body,
    ) {
    }

    /**
     * @return array<string, string[]>
     */
    public static function rules()
    {
        return [
            'article' => ['required', 'array'],
            'article.title' => ['sometimes', 'required', 'string'],
            'article.description' => ['sometimes', 'required', 'string'],
            'article.body' => ['sometimes', 'required', 'string'],
        ];
    }
}
