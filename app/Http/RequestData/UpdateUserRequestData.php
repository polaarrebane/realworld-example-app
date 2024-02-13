<?php

namespace App\Http\RequestData;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateUserRequestData extends Data
{
    public function __construct(
        #[MapInputName('user.username')]
        public readonly string|Optional $username,

        #[MapInputName('user.email')]
        public readonly string|Optional $email,

        #[MapInputName('user.password')]
        public readonly string|Optional $password,

        #[MapInputName('user.bio')]
        public readonly string|Optional $bio,

        #[MapInputName('user.image')]
        public readonly string|Optional $image,
    ) {
    }

    /**
     * @return array<string, array<int, Unique|string>|string>
     */
    public static function rules(): array
    {
        return [
            'user' => 'required|array',
            'user.username' => 'sometimes|required|string|unique:users,username',
            'user.email' => [
                'sometimes',
                'required',
                'email',

                /** @phpstan-ignore-next-line   */
                Rule::unique('users', 'email')->ignore(auth('api')->user()->id, 'id'),
            ],
            'user.password' => 'sometimes|required|string',
            'user.bio' => 'sometimes|required|string',
            'user.image' => 'sometimes|required|string',
        ];
    }
}
