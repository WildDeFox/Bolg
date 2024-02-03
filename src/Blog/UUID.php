<?php

namespace Blog\Defox\Blog;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;

readonly class UUID
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private string $uuidString
    )
    {
        if (!uuid_is_valid($uuidString)) {
            throw new InvalidArgumentException(
                "Неправельный UUID: $this->uuidString"
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function random(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }

    public function __toString(): string
    {
        return $this->uuidString;
    }
}