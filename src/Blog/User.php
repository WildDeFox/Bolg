<?php
namespace Blog\Defox\Blog;

use Blog\Defox\Person\Name;

class User
{
    public function __construct(
        private int $id,
        private Name $name,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return "Пользователь - $this->name, c ID - $this->id";
    }

}