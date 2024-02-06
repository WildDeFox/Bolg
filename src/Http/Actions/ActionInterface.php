<?php

namespace Blog\Defox\Http\Actions;

use Blog\Defox\Http\Request;
use Blog\Defox\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}