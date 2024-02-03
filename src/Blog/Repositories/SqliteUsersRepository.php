<?php

namespace Blog\Defox\Blog\Repositories;

use Blog\Defox\Blog\Exceptions\InvalidArgumentException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Person\Name;
use PDO;
use PDOStatement;

readonly class SqliteUsersRepository implements UserRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name, uuid, username)
                    VALUES (:first_name, :last_name, :uuid, :username)'
        );
        $statement->execute([
            ':first_name' => $user->name()->getFirstName(),
            ':last_name' => $user->name()->getLastName(),
            ':uuid' => $user->uuid(),
            ':username' => $user->getUsername(),
        ]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([$uuid]);

        return $this->getUser($statement, $uuid);
    }


    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function getByUsername($username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = ?'
        );
        $statement->execute([$username]);

        return $this->getUser($statement, $username);
    }


    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    private function getUser(PDOStatement $statement, string $errorString): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Не найден пользователь: $errorString"
            );
        }
        return new User(
            new UUID($result['uuid']),
            new Name($result['first_name'], $result['last_name']),
            $result['username']
        );
    }
}