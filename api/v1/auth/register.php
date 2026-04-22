<?php

$input = json_decode(file_get_contents('php://input'), true) ?? [];

require_fields(['email', 'password', 'username'], $input);

$email    = trim($input['email']);
$password = $input['password'];
$username = trim($input['username']);

$pdo = db();

// Check existing
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    json_error('Email already registered', 409);
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO users (email, password, username) VALUES (?, ?, ?)');
$stmt->execute([$email, $hash, $username]);

$userId = $pdo->lastInsertId();

$token = create_token([
    'sub'      => $userId,
    'email'    => $email,
    'username' => $username,
]);

json_success([
    'token' => $token,
    'user'  => [
        'id'       => $userId,
        'email'    => $email,
        'username' => $username,
    ]
], 'Registered successfully', 201);
