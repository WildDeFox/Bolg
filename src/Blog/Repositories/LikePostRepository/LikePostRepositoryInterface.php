<?php

namespace Blog\Defox\Blog\Repositories\LikePostRepository;

use Blog\Defox\Blog\LikePost;
use Blog\Defox\Blog\UUID;

interface LikePostRepositoryInterface
{
    public function save(LikePost $likePost): void;
    public function getByPostUuid(UUID $postUuid): array;
}