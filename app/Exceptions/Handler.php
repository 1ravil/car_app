<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ApiException) {
            return $e->render();
        }

        if ($e instanceof ModelNotFoundException) {
            return (new NotFoundException('Ресурс не найден'))->render();
        }

        if ($e instanceof ValidationException) {
            return (new ApiException(
                'unprocessable_entity',
                'Ошибка валидации',
                ['errors' => $e->errors()]
            ))->render();
        }

        return parent::render($request, $e);
    }
}
