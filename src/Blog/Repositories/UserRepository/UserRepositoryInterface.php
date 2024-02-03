<?php

namespace Blog\Defox\Blog\Repositories\UserRepository;

use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}