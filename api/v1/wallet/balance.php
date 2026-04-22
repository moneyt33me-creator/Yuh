<?php

$user = require_auth();
$pdo  = db();

$stmt = $pdo->prepare('SELECT balance FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user['sub']]);
$row = $stmt->fetch();

if (!$row) {
    json_error('User not found', 404);
}

json_success(['balance' => (float)$row['balance']], 'Balance fetched');
