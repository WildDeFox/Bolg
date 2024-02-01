<?php
namespace Blog\Defox\Blog;

class Comment
{
    public function __construct(
        private int           $id,
        private readonly User $userId,
        private readonly Post $postId,
        private string        $post
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

    public function getUserId(): int
    {
        return $this->userId->getId();
    }

    public function getPostId(): int
    {
        return $this->postId->getId();
    }

    public function getPost(): string
    {
        return $this->post;
    }

    public function setPost(string $post): void
    {
        $this->post = $post;
    }
}