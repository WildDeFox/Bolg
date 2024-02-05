<?php

namespace Blog\Defox\Http;

use Blog\Defox\Blog\Exceptions\HttpException;

readonly class Request
{
    public function __construct(
        private array $get, // $_GET
        private array $server // $_SERVER
    )
    {
    }

    // Метод для получения пути запроса
    // Например, для http://example.com/some/page?x=1&y=acb
    // путём будет строка '/some/page'
    /**
     * @throws HttpException
     */
    public function path(): string
    {
        // В суперглобальном массиве $_SERVER
        // значение URI хранится под ключом REQUEST_URI
        if (!array_key_exists('REQUEST_URI', $this->server)) {
            // Если мы не можем получить URI - бросам исключение
            throw new HttpException('Cannot get path from the request');
        }
        // Используем встроенную в PHP функцию parse_url
        $components = parse_url($this->server['REQUEST_URI']);
        if (!is_array($components) || !array_key_exists('path', $components)) {
            // Если мы не можем получить путь - бросаем исключение
            throw new HttpException('Cannot get path from the request');
        }
        return $components['path'];
    }

    // Метод для получениия значения
    // определённого параметра сттроки запроса
    // Например, для http://example.com/some/page?x=1&y=acb
    // значением параметра x будет строка '1'
    /**
     * @throws HttpException
     */
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            // Если нет такого параметра в запросе - бросаем исключение
            throw new HttpException("No such query param in the request: $param");
        }
        $value = trim($this->get[$param]);
        if (empty($value)) {
            // Если значение параметра пусто - бросаем исключение
            throw new HttpException("Empty query param in the request: $param");
        }
        return $value;
    }

    // Метод для получения значения
    // определённого заголовка
    /**
     * @throws HttpException
     */
    public function header(string $header): string
    {
        // В суперглобальном массиве $_SERVER
        // имена заголовков имеют префикс 'HTTP_'
        // а знаки подчеркивания заменены на минусы
        $headerName = mb_strtoupper("http_" . str_replace('-', '-', $header));
        if (!array_key_exists($headerName, $this->server)) {
            throw new HttpException("No such header in the request: $header");
        }
        $value = trim($this->server[$headerName]);
        if (empty($value)) {
            throw new HttpException("Empty header in the request: $header");
        }
        return $value;
    }

}