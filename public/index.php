<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// DEBUG TEST
if (isset($_GET['test_die'])) {
    die("<h1>INDEX.PHP EST BIEN ACCESSIBLE</h1>");
}

define('LARAVEL_START', microtime(true));

try {
    $autoload = __DIR__.'/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        die("Erreur Fatale : Le fichier autoload n'existe pas a l'emplacement : $autoload");
    }
    require $autoload;

    $app_file = __DIR__.'/../bootstrap/app.php';
    if (!file_exists($app_file)) {
        die("Erreur Fatale : Le fichier bootstrap/app.php n'existe pas a l'emplacement : $app_file");
    }
    $app = require_once $app_file;

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();

    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo "<h1>Exception capturée !</h1>";
    echo "<strong>Message :</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Fichier :</strong> " . $e->getFile() . " (Ligne " . $e->getLine() . ")<br>";
    echo "<h2>Trace :</h2><pre>" . $e->getTraceAsString() . "</pre>";
}
