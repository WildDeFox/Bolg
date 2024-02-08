<?php

namespace Blog\Defox\Http\Actions\Posts;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Exceptions\PostNotFoundException;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepository;
use Blog\Defox\Blog\Repositories\PostRepository\PostRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $post = $this->postRepository->get(new UUID($uuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'uuid' => $uuid,
            'title' => $post->getTitle()
        ]);
    }
}