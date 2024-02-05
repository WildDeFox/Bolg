<?php

namespace Blog\Defox\Http;

use JsonException;

abstract class Response
{
    // Абстрактный класс ответа,
    // содержащий общую функциональность
    // успешного и неуспешного ответа

    // Маркировка успешности ответа
    protected const SUCCESS = true;

    /**
     * @throws JsonException
     */
    public function send(): void
    {
        // Данные ответ:
        // маркировка успешности и полезные данные
        $data = ['success' => static::SUCCESS] + $this->payload();

        // Отправляем заголовок, говорящий, что в теле ответа будет JSON
        header('Content-Type: application/json');

        // Кодируем данные в JSON и отправляем их в теле ответа
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    abstract protected function payload(): array;
}