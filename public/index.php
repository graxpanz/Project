<?php
$debug = true;

// ตั้งค่า error reporting และ timezone
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Bangkok');

// เริ่ม session
session_start();

// กำหนด constants
define('BASE_PATH', dirname(__DIR__));

// Autoload classes
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// โหลด configuration files
require_once BASE_PATH . '/config/database.php';

// โหลด core files
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Route.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/Redirect.php';

// โหลด routes
require_once BASE_PATH . '/routes/web.php';

// สร้าง Router instance และ dispatch request
$router = new Router(Route::getRoutes());

try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    // จัดการกับ exceptions
    if ($debug) {
        // แสดงรายละเอียดข้อผิดพลาดในโหมด development
        echo "<h1>Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        // แสดงหน้า 404 ในโหมด production
        http_response_code(404);
        require_once BASE_PATH . '/views/errors/404.php';
    }
    exit();
}