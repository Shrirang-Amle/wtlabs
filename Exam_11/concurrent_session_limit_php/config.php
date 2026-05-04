<?php

declare(strict_types=1);

const MAX_CONCURRENT_SESSIONS = 3;
const SESSION_TIMEOUT_SECONDS = 300;
const APP_DATA_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'data';
const APP_SESSION_DIR = APP_DATA_DIR . DIRECTORY_SEPARATOR . 'sessions';
const SESSION_REGISTRY_FILE = APP_DATA_DIR . DIRECTORY_SEPARATOR . 'session_registry.json';

if (!is_dir(APP_SESSION_DIR)) {
    mkdir(APP_SESSION_DIR, 0777, true);
}

if (!file_exists(SESSION_REGISTRY_FILE)) {
    file_put_contents(SESSION_REGISTRY_FILE, json_encode(new stdClass(), JSON_PRETTY_PRINT));
}

session_save_path(APP_SESSION_DIR);
session_set_cookie_params([
    'lifetime' => SESSION_TIMEOUT_SECONDS,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);

