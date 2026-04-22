<?php

$user = require_auth();
$pdo  = db();

$stmt = $pdo->prepare('SELECT game_name, bet_amount, win_amount, result, created_at FROM game_rounds WHERE user_id = ? ORDER BY created_at DESC LIMIT 100');
$stmt->execute([$user['sub']]);
$rows = $stmt->fetchAll();

json_success(['history' => $rows], 'Game history');
