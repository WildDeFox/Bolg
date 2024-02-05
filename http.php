<?php

use Blog\Defox\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER);
try {
    $path = $request->query('cookie');
} catch (Exception $e) {
}