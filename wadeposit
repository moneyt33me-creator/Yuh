<?php

$user  = require_auth();
$input = json_decode(file_get_contents('php://input'), true) ?? [];

require_fields(['amount'], $input);

$amount = (float)$input['amount'];
if ($amount <= 0) {
    json_error('Amount must be greater than zero', 422);
}

$pdo = db();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare('UPDATE users SET balance = balance + ? WHERE id = ?');
    $stmt->execute([$amount, $user['sub']]);

    $stmt = $pdo->prepare('INSERT INTO transactions (user_id, type, amount) VALUES (?, "deposit", ?)');
    $stmt->execute([$user['sub'], $amount]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    json_error('Deposit failed', 500, ['error' => $e->getMessage()]);
}

json_success(['amount' => $amount], 'Deposit successful');
