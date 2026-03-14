<?php

/**
 * Custom LWS Proxy for Laravel
 */

$publicPath = __DIR__ . '/public';

// Si le fichier demandé existe physiquement dans /public, on le sert
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

// Sinon, on charge l'index de Laravel
require_once $publicPath . '/index.php';
