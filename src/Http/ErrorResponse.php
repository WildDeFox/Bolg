<?php

namespace Blog\Defox\Http;

class ErrorResponse extends Response
{
    protected const false SUCCESS = false;
    // Неуспешный ответ содержит строку с причиной неуспеха,
    // по умолчанию - 'Something goes wrong'
    public function __construct(
        private readonly string $reason = 'Something goes wrong'
    )
    {
    }

    protected function payload(): array
    {
        return ['reason' => $this->reason];
    }
}