<?php
define('LARAVEL_START', microtime(true));
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic CICA-PRO v2</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current File: " . __FILE__ . "<br>";

$base_dir = realpath(__DIR__ . '/..');
echo "Base Directory: " . $base_dir . "<br>";

$db_path = $base_dir . '/database/database.sqlite';
echo "Checking SQLite DB at: " . $db_path . "<br>";
if (file_exists($db_path)) {
    echo "✅ SQLite file exists.<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($db_path)), -4) . "<br>";
    if (is_writable($db_path)) {
        echo "✅ SQLite file is writable.<br>";
    } else {
        echo "❌ SQLite file is NOT writable.<br>";
    }
} else {
    echo "❌ SQLite file MISSING.<br>";
}

$storage_paths = [
    $base_dir . '/storage',
    $base_dir . '/storage/logs',
    $base_dir . '/storage/framework',
    $base_dir . '/storage/framework/sessions',
    $base_dir . '/storage/framework/views',
    $base_dir . '/storage/framework/cache',
    $base_dir . '/bootstrap/cache',
];

echo "<h2>Checking Permissions:</h2>";
foreach ($storage_paths as $path) {
    echo "$path : ";
    if (is_dir($path)) {
        if (is_writable($path)) {
            echo "✅ Writable (" . substr(sprintf('%o', fileperms($path)), -4) . ")";
        } else {
            echo "❌ NOT Writable (" . substr(sprintf('%o', fileperms($path)), -4) . ")";
        }
    } else {
        echo "❌ MISSING";
    }
    echo "<br>";
}

echo "<h2>PHP Info:</h2>";
phpinfo();
