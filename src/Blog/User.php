<?php
namespace Blog\Defox\Blog;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Person\Name;

readonly class User
{
    public function __construct(
        private UUID   $uuid,
        private Name   $name,
        private string $username,
        private string $hashedPassword,
    )
    {
    }

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    // Функция для вычисления хеша
    public static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    // Функция для проверки предъявленного пароля
    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    // Функция для создания нового пользователя
    /**
     * @throws InvalidArgumentException
     */
    public static function createFrom(
        Name $name,
        string $username,
        string $password,
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $name,
            $username,
            self::hash($password, $uuid)
        );
    }
    public function getUsername(): string
    {
        return $this->username;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return "Пользователь: $this->name, c UUID: $this->uuid и логином $this->username";
    }

}