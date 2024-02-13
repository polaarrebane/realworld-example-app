<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class CreateCommentRequestData extends Data
{
    public function __construct(
        #[MapInputName('comment.body')]
        public readonly string $body,
    ) {
    }

    /**
     * @return array<string, string[]>
     */
    public static function rules(): array
    {
        return [
            'comment.body' => ['required', 'string'],
        ];
    }
}
