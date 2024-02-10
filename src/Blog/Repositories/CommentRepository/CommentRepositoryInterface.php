<?php

namespace Blog\Defox\Blog\Repositories\CommentRepository;

use Blog\Defox\Blog\Comment;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\UUID;

interface CommentRepositoryInterface
{
    public function get(UUID $uuid): Comment;
    public function save(Comment $comment): void;
}