<?php

namespace Blog\Defox\Http\Actions\Auth;

use Blog\Defox\Blog\AuthToken;
use Blog\Defox\Blog\Exceptions\Authexception;
use Blog\Defox\Blog\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\Auth\PasswordAuthentication;
use Blog\Defox\Http\Auth\PasswordAuthenticationInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;
use Random\RandomException;

class Login implements ActionInterface
{

    public function __construct(
        // Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository,
    )
    {

    }

    /**
     * @throws RandomException
     */
    public function handle(Request $request): Response
    {
        // Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (Authexception $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Генерируем токен
        $authToken = new AuthToken(
            // Случайная строка длинной 40 символов
        bin2hex(random_bytes(40)),
            $user->uuid(),
            // Срок годности - 1 день
            (new \DateTimeImmutable())->modify('+1 day')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}