<?php

namespace Blog\Defox\Http\Auth;

use Blog\Defox\Blog\Exceptions\Authexception;
use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\User;
use Blog\Defox\Http\Request;

readonly class JsonBodyUsernameIdentification implements IdentificationInterface
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * @throws Authexception
     */
    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new Authexception($e->getMessage());
        }

        try {
            return $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new Authexception($e->getMessage());
    }
    }
}