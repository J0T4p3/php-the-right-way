<?php
require_once __DIR__ . "/../src/Controllers/taskController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new TaskController();

if ($uri === '/' && $method === 'GET') {
    $controller->index();
} elseif ($uri === '/create' && $method === 'POST') {
    $controller->create();
} elseif ($uri === '/toggle' && $method === 'POST') {
    $controller->toggle();
} elseif ($uri === '/delete' && $method === 'POST') {
    $controller->delete();
} else {
    http_response_code(404);
    echo "Not Found";
}
