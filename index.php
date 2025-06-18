<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

// Create logs directory and simple logging function
$logsDir = __DIR__ . '/storage/logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

function logError($message) {
    $logFile = __DIR__ . '/storage/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

use App\Core\Router;
use App\Middleware\AdminMiddleware;

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->group(['prefix' => '/admin', 'middleware' => [AdminMiddleware::class]], function($router) {
    require __DIR__ . '/routes/admin.php';
});

$router->setNotFoundHandler(function() {
    echo "404 - Page not found";
});

// Set up error handler
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Set up exception handler
set_exception_handler(function($exception) {
    logError('Uncaught exception: ' . $exception->getMessage());
    
    // Check if this is an API request or web request
    $isApiRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === 0;
    
    if ($isApiRequest) {
        // Return JSON for API requests
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => 'Internal server error'
        ]);
    } else {
        // For web requests, just show a simple error page instead of redirecting
        http_response_code(500);
        echo '<h1>Something went wrong</h1>';
        echo '<p>An error occurred. Please try again later.</p>';
        echo '<a href="/">Go back to home</a>';
    }
    exit;
});

$router->dispatch();