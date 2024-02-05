<?php

use Blog\Defox\Http\Request;
use Blog\Defox\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER);

$response = new SuccessfulResponse([
    'message' => 'Hello from PHP'
]);
try {
    $response->send();
} catch (Exception) {
}