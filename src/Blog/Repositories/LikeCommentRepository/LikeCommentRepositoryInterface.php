<?php

namespace Blog\Defox\Blog\Repositories\LikeCommentRepository;

use Blog\Defox\Blog\LikeComment;
use Blog\Defox\Blog\UUID;

interface LikeCommentRepositoryInterface
{
    public function save(LikeComment $likeComment): void;
    public function getByCommentsUuid(UUID $uuid): array;
}