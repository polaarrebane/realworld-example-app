<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class CreateArticleRequestData extends Data
{
    public function __construct(
        #[MapInputName('article.title')]
        public readonly string $title,

        #[MapInputName('article.description')]
        public readonly string $description,

        #[MapInputName('article.body')]
        public readonly string $body,

        /**
         * @var string[]
         */
        #[MapInputName('article.tagList')]
        public readonly array $tagList,
    ) {
    }

    /**
     * @return array<string, string[]>
     */
    public static function rules()
    {
        return [
            'article.title' => ['required', 'string'],
            'article.description' => ['required', 'string'],
            'article.body' => ['required', 'string'],
            'article.tagList' => ['required', 'array'],
            'article.tagList.*' => ['required', 'string'],
        ];
    }
}
