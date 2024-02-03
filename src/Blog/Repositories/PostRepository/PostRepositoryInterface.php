<?php

namespace Blog\Defox\Blog\Repositories\PostRepository;

use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\UUID;

interface PostRepositoryInterface
{
//    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
}