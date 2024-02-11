<?php

namespace Blog\Defox\Blog\Repositories\AuthTokensRepository;

use Blog\Defox\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
    public function save(AuthToken $authToken): void;

    public function get(string $token): AuthToken;
}