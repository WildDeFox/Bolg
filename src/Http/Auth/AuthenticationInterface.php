<?php

namespace Blog\Defox\Http\Auth;

use Blog\Defox\Blog\User;
use Blog\Defox\Http\Request;

interface AuthenticationInterface
{
    public function user(Request $request): User;
}