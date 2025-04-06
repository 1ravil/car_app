<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected string $errorCode;
    protected ?array $data;

    public function __construct(string $errorCode, string $message = '', ?array $data = null)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        $response = [
            'code' => $this->errorCode,
            'message' => $this->getMessage() ?: $this->getDefaultMessage(),
        ];

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        return response()->json($response, $this->getHttpStatusCode());
    }

    protected function getDefaultMessage(): string
    {
        return match($this->errorCode) {
            'not_found' => 'Ресурс не найден',
            'forbidden' => 'Доступ запрещен',
            'bad_request' => 'Некорректный запрос',
            'unprocessable_entity' => 'Ошибка валидации',
            default => 'Произошла ошибка',
        };
    }

    protected function getHttpStatusCode(): int
    {
        return match($this->errorCode) {
            'forbidden' => 403,
            'not_found' => 404,
            'method_not_allowed' => 405,
            'bad_request' => 400,
            'unprocessable_entity' => 422,
            default => 500,
        };
    }
}
