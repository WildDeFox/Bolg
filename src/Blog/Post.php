<?php
namespace Blog\Defox\Blog;
class Post
{
    public function __construct(
        private int           $id,
        private readonly User $userId,
        private string        $title,
        private string        $text,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId():int
    {
        return $this->userId->getId();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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
}