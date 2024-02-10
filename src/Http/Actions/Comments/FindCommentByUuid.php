<?php

namespace Blog\Defox\Http\Actions\Comments;

use Blog\Defox\Blog\Exceptions\CommentNotFoundException;
use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Repositories\CommentRepository\CommentRepositoryInterface;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;

readonly class FindCommentByUuid implements ActionInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
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
            $comment = $this->commentRepository->get(new UUID($uuid));
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'uuid' => $uuid,
            'text' => $comment->getText()
        ]);
    }
}