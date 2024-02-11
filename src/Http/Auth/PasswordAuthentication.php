<?php

namespace Blog\Defox\Http\Auth;

use Blog\Defox\Blog\Exceptions\Authexception;
use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\User;
use Blog\Defox\Http\Request;

class PasswordAuthentication implements PasswordAuthenticationInterface
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
        // 1. Идентифицируем пользователя
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new Authexception($e->getMessage());
        }
        try {
            $user = $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new Authexception($e->getMessage());
        }

        // 2. Аутентифицируем пользователя.
        // Проверяем, что предъявленный пароль
        // соответствует сохранённому в БД
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new Authexception($e->getMessage());
        }

        if (!$user->checkPassword($password)) {
            // Если пароли не совпадают - бросаем исключение
            throw new Authexception('Wrong password');
        }
        return $user;
    }
}