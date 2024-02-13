<?php

namespace App\Http\RequestData;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class RegisterUserRequestData extends Data
{
    public function __construct(
        #[MapInputName('user.username')]
        public readonly string $username,

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
            'user' => ['required'],
            'user.username' => ['required', 'string:min(5)', 'unique:users,username'],
            'user.email' => ['required', 'email', 'unique:users,email'],
            'user.password' => ['required', 'string:min(8)'],
        ];
    }
}
