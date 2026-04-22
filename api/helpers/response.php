<?php

function json_success(array $data = [], string $message = 'OK', int $code = 200): void
{
    http_response_code($code);
    echo json_encode([
        'success' => true,
        'message' => $message,
        'data'    => $data,
    ]);
    exit;
}

function json_error(string $message = 'Error', int $code = 400, array $errors = []): void
{
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message,
        'errors'  => $errors,
    ]);
    exit;
}
