<?php
namespace Blog\Defox\Blog;

class Comment
{
    public function __construct(
        private readonly UUID $uuid,
        private readonly Post $post,
        private readonly User $user,
        private string        $text,
    )
    {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPost(): Post
    {
        return $this->post;
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