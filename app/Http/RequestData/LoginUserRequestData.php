<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class LoginUserRequestData extends Data
{
    public function __construct(

        #[MapInputName('user.email')]
        public readonly string $email,

        #[MapInputName('user.password')]
        public readonly string $password,
    ) {
    }

    /**
     * @return array<int|string, string[]>
     */
    public static function rules(): array
    {
        return [
            'user.email' => ['required', 'email'],
            'user.password' => ['required', 'string:min(8)'],
        ];
    }
}
