<?php

namespace Blog\Defox\Blog;

readonly class LikeComment
{
    public function __construct(
        private UUID    $uuid,
        private User    $user,
        private Comment $comment,
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

    public function getComment(): Comment
    {
        return $this->comment;
    }
}