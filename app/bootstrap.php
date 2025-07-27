<?php
// Application bootstrap

// -----------------------------------------------------------------
// Autoloader (PSR-4ish simple implementation without Composer)
// -----------------------------------------------------------------
spl_autoload_register(function ($class) {
    // Convert namespace to full file path relative to project root
    $prefix = '';
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR; // /app

    // Remove leading backslashes
    $class = ltrim($class, '\\');

    // Replace namespace separators with directory separators in the relative class name
    $relativeClass = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    $file = $baseDir . $relativeClass;
    if (is_file($file)) {
        require_once $file;
    }
});

// -----------------------------------------------------------------
// Load configuration
// -----------------------------------------------------------------
$config = require __DIR__ . '/../config/config.php';

// -----------------------------------------------------------------
// Set error handling (simple; extend with Monolog later)
// -----------------------------------------------------------------
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// -----------------------------------------------------------------
// Start secure session
// -----------------------------------------------------------------
$coreSession = new Core\Session();
$coreSession->start();

// -----------------------------------------------------------------
// Database connection (PDO)
// -----------------------------------------------------------------
$dbConf = $config['db'];
$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $dbConf['host'], $dbConf['name'], $dbConf['charset']);
try {
    $pdo = new PDO($dsn, $dbConf['user'], $dbConf['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection error');
}

// Make PDO accessible via container-like global registry (simple)
Core\Model::setConnection($pdo);

// -----------------------------------------------------------------
// Load routes and dispatch request via Router
// -----------------------------------------------------------------
$router = new Core\Router($config['app']['base_url']);
require __DIR__ . '/../config/routes.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);