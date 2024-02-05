<?php

namespace Commands;

use Blog\Defox\Blog\Commands\Arguments;
use Blog\Defox\Blog\Commands\CreateUserCommand;
use Blog\Defox\Blog\Exceptions\ArgumentsException;
use Blog\Defox\Blog\Exceptions\CommandException;
use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Repositories\UserRepository\DummyUsersRepository;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    /**
     * @throws ArgumentsException
     * @throws InvalidArgumentException
     */
    public function testItThrowAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUserCommand(new DummyUsersRepository());
        // Описываем тип ожидаемого исключения
        $this->expectException(CommandException::class);

        // и его сообщение
        $this->expectExceptionMessage('User already exists: Ivan');

        // Запускаем команду с аркументами
        $command->handle(new Arguments(['username' => 'Ivan']));
    }

    // Тест проверяет, что команда действително требует фамилию пользователя

    /**
     * @throws CommandException
     * @throws InvalidArgumentException
     */
    public function testItRequiresLastName(): void
   {
       $command = new CreateUserCommand($this->makeUsersRepository());
       $this->expectException(ArgumentsException::class);
       $this->expectExceptionMessage('No such argument: last_name');
       $command->handle(new Arguments(['username' => 'Ivan', 'first_name' => 'Ivan',]));
   }

    // Функция возвращает объект типа UsersRepositoryInterface
    private function makeUsersRepository(): UserRepositoryInterface
    {
        return new class implements UserRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
        };
        {

        }
    }
}