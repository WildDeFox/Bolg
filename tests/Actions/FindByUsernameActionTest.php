<?php

namespace Actions;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\Users\FindByUsername;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\SuccessfulResponse;
use Blog\Defox\Person\Name;
use JsonException;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{
    // Запускаем тест в отдельном процессе
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws /JsonException
     */

    // Тест, проверяющий, что будет возвращён неудачный ответ,
    // если в запросе нет параметра username
    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        // Создаём объект запроса
        // Вместо суперглобальных переменных передаём простые массивы
        $request = new Request([], [], "");

        // Создаём стаб репозитория пользователей
        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');
        $response->send();
    }

    /**
     * @throws JsonException
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        // Теперь запрос будет иметь параметр username
        $request = new Request(['username' => 'ivan'], [], '');
        // Репозиторий пользователей по прежнему пуст
        $usersRepository = $this->usersRepository([]);
        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"Not found"}');
        $response->send();
    }

    // Тест, проверяющий, что будет возвращён удачный ответ,
    // если пользователь найден
    /**
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['username' => 'ivan'], [], '');
        // На этот раз в репозитории есть нужный нам пользователь
        $usersRepository = $this->usersRepository([
            new User(
                UUID::random(),
                new Name('Ivan', 'Nikita'),
                'ivan'
            ),
        ]);
        $action = new FindByUsername($usersRepository);
        $response = $action->handle($request);
        // Проверяем, что ответ - удачный
        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"username":"ivan","name":"Ivan Nikita"}}');
        $response->send();

    }

    // Функция, создающая стаб репозитория пользователей,
    // принимает массив "существующих" пользователей
    private function usersRepository(array $users): UserRepositoryInterface
    {
        // В конструктор анонимного класса передаём массив пользователей
        return new readonly class($users) implements UserRepositoryInterface {
            public function __construct(
                private array $users
            )
            {
            }

            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUsername()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }



}