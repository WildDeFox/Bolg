<?php
namespace Blog\Defox\Blog;

use Blog\Defox\Person\Name;

class User
{
    public function __construct(
        private readonly UUID $uuid,
        private Name          $name,
        private string        $username,
    )
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }


}