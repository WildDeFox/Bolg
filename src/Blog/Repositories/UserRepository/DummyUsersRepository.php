<?php

namespace Blog\Defox\Blog\Repositories\UserRepository;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Person\Name;

class DummyUsersRepository implements UserRepositoryInterface
{

    #[\Override] public function save(User $user): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @throws UserNotFoundException
     */
    #[\Override] public function get(UUID $uuid): User
    {
        throw new UserNotFoundException("Not found");
    }

    /**
     * @throws InvalidArgumentException
     */
    #[\Override] public function getByUsername(string $username): User
    {
        return new User(UUID::random(), new Name("first", "last"), "user123");
    }
}