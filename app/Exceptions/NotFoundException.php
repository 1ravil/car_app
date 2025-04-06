<?php

namespace App\Exceptions;

class NotFoundException extends ApiException
{
    public function __construct(string $message = '', ?array $data = null)
    {
        parent::__construct('not_found', $message, $data);
    }
}
