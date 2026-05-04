<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cleanInput(string $value): string
{
    return trim($value);
}

function setUserCookie(string $name): void
{
    setcookie('remember_user', $name, time() + (86400 * 30), '/');
}

function removeUserCookie(): void
{
    setcookie('remember_user', '', time() - 3600, '/');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
