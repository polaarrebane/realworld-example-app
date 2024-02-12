<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait RenderValidationExceptionAsJson
{
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response
    {
        if ($e->response) {
            return $e->response;
        }

        return response()->json(
            data  : ['errors' => ['body' => $this->implodeErrors($e->errors())]],
            status: 422,
        );
    }

    /**
     * @param  array<string, string[]>  $errors
     */
    protected function implodeErrors(array $errors): string
    {
        return collect($errors)->implode(
            value: static fn ($messages) => implode(
                separator: ' ',
                array    : $messages
            ),
            glue : ' '
        );
    }
}
