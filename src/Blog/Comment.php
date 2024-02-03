<?php
namespace Blog\Defox\Blog;

class Comment
{
    public function __construct(
        private readonly UUID $uuid,
        private readonly UUID $postUuid,
        private readonly UUID $userUuid,
        private string        $text,
    )
    {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function getUserUuid(): UUID
    {
        return $this->userUuid;
    }

    public function getPostUuid(): UUID
    {
        return $this->postUuid;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText ($text): void
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Комментарий с текстом: $this->text";
    }
}