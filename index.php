<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Normalize (remove trailing slash)
$uri = rtrim($uri, '/');

// Base prefix
$prefix = '/api/v1';

if (strpos($uri, $prefix) !== 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Not Found']);
    exit;
}

$path = substr($uri, strlen($prefix)); // e.g. /auth/login

switch (true) {
    // Auth
    case $path === '/auth/login' && $method === 'POST':
        require __DIR__ . '/api/v1/auth/login.php';
        break;
    case $path === '/auth/register' && $method === 'POST':
        require __DIR__ . '/api/v1/auth/register.php';
        break;
    case $path === '/auth/logout' && $method === 'POST':
        require __DIR__ . '/api/v1/auth/logout.php';
        break;

    // Wallet
    case $path === '/wallet/balance' && $method === 'GET':
        require __DIR__ . '/api/v1/wallet/balance.php';
        break;
    case $path === '/wallet/deposit' && $method === 'POST':
        require __DIR__ . '/api/v1/wallet/deposit.php';
        break;
    case $path === '/wallet/withdraw' && $method === 'POST':
        require __DIR__ . '/api/v1/wallet/withdraw.php';
        break;

    // Games
    case $path === '/games/list' && $method === 'GET':
        require __DIR__ . '/api/v1/games/list.php';
        break;
    case $path === '/games/play' && $method === 'POST':
        require __DIR__ . '/api/v1/games/play.php';
        break;
    case $path === '/games/history' && $method === 'GET':
        require __DIR__ . '/api/v1/games/history.php';
        break;

    // Admin
    case $path === '/admin/login' && $method === 'POST':
        require __DIR__ . '/api/v1/admin/login.php';
        break;
    case $path === '/admin/users' && $method === 'GET':
        require __DIR__ . '/api/v1/admin/users.php';
        break;
    case $path === '/admin/transactions' && $method === 'GET':
        require __DIR__ . '/api/v1/admin/transactions.php';
        break;
    case $path === '/admin/dashboard' && $method === 'GET':
        require __DIR__ . '/api/v1/admin/dashboard.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Route not found']);
}
