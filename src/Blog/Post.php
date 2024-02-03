<?php
namespace Blog\Defox\Blog;
class Post
{
    public function __construct(
        private UUID $uuid,
        private UUID $userId,
        private string        $title,
        private string        $text,
    )
    {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function getUserId(): UUID
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Пост: $this->uuid, $this->title, $this->text";
    }
}