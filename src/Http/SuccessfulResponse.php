<?php
declare(strict_types=1);

namespace Blog\Defox\Http;

class SuccessfulResponse extends Response
{
    protected const true SUCCESS = true;
    // Успешный ответ содержит массив с данными,
    // по умолчанию - пустой
    public function __construct(
        private readonly array $data = []
    )
    {
    }
    // Реализация абстрактного метода
    // родительского класса
    protected function payload(): array
    {
        return ['data' => $this->data];
    }
}