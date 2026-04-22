<?php

$user  = require_auth();
$input = json_decode(file_get_contents('php://input'), true) ?? [];

require_fields(['game_code', 'bet_amount'], $input);

$gameCode   = $input['game_code'];
$betAmount  = (float)$input['bet_amount'];

if ($betAmount <= 0) {
    json_error('Bet amount must be greater than zero', 422);
}

$pdo = db();
$pdo->beginTransaction();

try {
    // Lock balance
    $stmt = $pdo->prepare('SELECT balance FROM users WHERE id = ? FOR UPDATE');
    $stmt->execute([$user['sub']]);
    $row = $stmt->fetch();

    if (!$row) {
        throw new Exception('User not found');
    }

    if ((float)$row['balance'] < $betAmount) {
        throw new Exception('Insufficient balance');
    }

    // Simple fake game logic: 50% chance win 2x
    $win = (mt_rand(0, 1) === 1);
    $winAmount = $win ? $betAmount * 2 : 0.0;
    $result    = $win ? 'win' : 'lose';

    $newBalance = (float)$row['balance'] - $betAmount + $winAmount;

    $stmt = $pdo->prepare('UPDATE users SET balance = ? WHERE id = ?');
    $stmt->execute([$newBalance, $user['sub']]);

    $stmt = $pdo->prepare('INSERT INTO game_rounds (user_id, game_name, bet_amount, win_amount, result) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$user['sub'], $gameCode, $betAmount, $winAmount, $result]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    json_error($e->getMessage(), 422);
}

json_success([
    'game_code'   => $gameCode,
    'bet_amount'  => $betAmount,
    'win_amount'  => $winAmount,
    'result'      => $result,
    'new_balance' => $newBalance,
], 'Game played');
