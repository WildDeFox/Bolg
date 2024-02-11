<?php

namespace Blog\Defox\Http\Auth;

use Blog\Defox\Blog\User;
use Blog\Defox\Http\Request;

interface IdentificationInterface
{
    // Контракт описывает единственный метод,
    // получающий пользователя из запроса
    public function user(Request $request): User;
}