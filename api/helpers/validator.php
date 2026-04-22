<?php

function require_fields(array $fields, array $data): void
{
    $missing = [];

    foreach ($fields as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            $missing[] = $field;
        }
    }

    if (!empty($missing)) {
        json_error('Missing required fields', 422, ['missing' => $missing]);
    }
}
