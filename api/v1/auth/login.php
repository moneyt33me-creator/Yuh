<?php

$input = json_decode(file_get_contents('php://input'), true) ?? [];

require_fields(['email', 'password'], $input);

$email    = trim($input['email']);
$password = $input['password'];

$pdo = db();

$stmt = $pdo->prepare('SELECT id, email, username, password, is_admin FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    json_error('Invalid credentials', 401);
}

$token = create_token([
    'sub'      => $user['id'],
    'email'    => $user['email'],
    'username' => $user['username'],
    'is_admin' => (bool)$user['is_admin'],
]);

json_success([
    'token' => $token,
    'user'  => [
        'id'       => $user['id'],
        'email'    => $user['email'],
        'username' => $user['username'],
        'is_admin' => (bool)$user['is_admin'],
    ]
], 'Logged in successfully');
