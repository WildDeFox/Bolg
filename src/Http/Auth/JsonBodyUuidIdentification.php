<?php

namespace Blog\Defox\Http\Auth;

use Blog\Defox\Blog\Exceptions\Authexception;
use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Request;
use \Blog\Defox\Blog\User;
class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * @throws Authexception
     * @throws InvalidArgumentException
     */
    public function user(Request $request): User
    {
        try {
            // Получаем имя пользователя из JSON-тела запроса.
            // Ожидаем, что имя пользователя находится в поле username
            $userUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException $e) {
            // Если невозможно получить имя пользователя из запроса -
            // бросаем исключение
            throw new AuthException($e->getMessage());
        }
        try {
            // Ищем пользователя в репозитории и возвращаем его
            return $this->userRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
            // Если пользователь не найден -
            // бросаем исключение
            throw new Authexception($e->getMessage());
        }

    }
}