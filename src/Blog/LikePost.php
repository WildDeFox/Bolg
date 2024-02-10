<?php

namespace Blog\Defox\Blog;

readonly class LikePost
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $user,
    )
    {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}