<?php

use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Exceptions\UserNotFoundException;
use Blog\Defox\Blog\Post;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Person\Name;
use PHPUnit\Framework\TestCase;

class PostRepositoryTest extends TestCase
{

    /**
     * @throws UserNotFoundException
     * @throws \Blog\Defox\Blog\Exceptions\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repository = new PostRepository($connectionMock);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage("Пост: 123e4567-e89b-12d3-a456-426614174000 не найден");

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testItSavesPostToDatabase(): void
    {
        $connectionStab = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':user_uuid' => '123e4567-e89b-12d3-a456-426614174001',
                ':title' => 'title',
                ':text' => 'text',
            ]);
        $connectionStab->method('prepare')->willReturn($statementMock);
        $repository = new PostRepository($connectionStab);
        $repository->save(
            new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new User(
                    new UUID('123e4567-e89b-12d3-a456-426614174001'),
                    new Name('Ivan', 'Nikita'),
                    'ivan123'
                ),
                'title',
                'text'
            )
        );
    }
}