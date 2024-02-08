<?php

namespace Blog\Defox\Http\Actions\Users;

use Blog\Defox\Blog\Exceptions\HttpException;
use Blog\Defox\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Blog\Defox\Blog\User;
use Blog\Defox\Blog\UUID;
use Blog\Defox\Http\Actions\ActionInterface;
use Blog\Defox\Http\ErrorResponse;
use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;
use Blog\Defox\Http\SuccessfulResponse;
use Blog\Defox\Person\Name;

class CreateUser implements ActionInterface
{
 public function __construct(
     private UserRepositoryInterface $userRepository,
 )
 {
 }

 public function handle(Request $request): Response
 {
     try {
         $newUserUuid = UUID::random();

         $user = new User(
             $newUserUuid,
             new Name(
                 $request->jsonBodyField('first_name'),
                 $request->jsonBodyField('last_name')
             ),
             $request->jsonBodyField('username')
         );
     } catch (HttpException $e) {
         return new ErrorResponse($e->getMessage());
     }
     $this->userRepository->save($user);
     return new SuccessfulResponse([
         'uuid' => (string)$newUserUuid,
     ]);
 }
}