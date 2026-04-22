<?php

$claims = require_auth();

if (empty($claims['is_admin'])) {
    json_error('Forbidden', 403);
}

$pdo = db();

$stmt = $pdo->query('SELECT id, email, username, balance, is_admin, created_at FROM users ORDER BY created_at DESC LIMIT 200');
$rows = $stmt->fetchAll();

json_success(['users' => $rows], 'Users list');
