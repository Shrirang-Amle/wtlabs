<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

final class SessionManager
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function login(string $username): array
    {
        self::startSession();
        self::cleanupExpiredSessions();

        $sessionId = session_id();
        $registry = self::readRegistry();
        $activeSessions = $registry[$username] ?? [];

        if (!isset($activeSessions[$sessionId]) && count($activeSessions) >= MAX_CONCURRENT_SESSIONS) {
            return [
                'success' => false,
                'message' => 'Maximum concurrent session limit reached. Only 3 active sessions are allowed per user.',
            ];
        }

        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time();

        $activeSessions[$sessionId] = [
            'last_activity' => time(),
            'created_at' => $activeSessions[$sessionId]['created_at'] ?? time(),
        ];

        $registry[$username] = $activeSessions;
        self::writeRegistry($registry);

        return [
            'success' => true,
            'message' => 'Login successful.',
        ];
    }

    public static function validateAuthenticatedSession(): void
    {
        self::startSession();
        self::cleanupExpiredSessions();

        if (empty($_SESSION['username']) || empty($_SESSION['last_activity'])) {
            self::forceLogout();
        }

        if ((time() - (int) $_SESSION['last_activity']) > SESSION_TIMEOUT_SECONDS) {
            self::logoutCurrentSession();
            $_SESSION['flash_message'] = 'Session expired after 5 minutes of inactivity.';
            header('Location: index.php');
            exit;
        }

        $_SESSION['last_activity'] = time();
        self::touchRegistryEntry($_SESSION['username'], session_id());
    }

    public static function logoutCurrentSession(): void
    {
        self::startSession();

        if (!empty($_SESSION['username'])) {
            self::removeSession($_SESSION['username'], session_id());
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'] ?? false, $params['httponly'] ?? true);
        }

        session_destroy();
    }

    public static function getFlashMessage(): ?string
    {
        self::startSession();

        if (!isset($_SESSION['flash_message'])) {
            return null;
        }

        $message = (string) $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);

        return $message;
    }

    public static function getActiveSessionCount(string $username): int
    {
        self::cleanupExpiredSessions();
        $registry = self::readRegistry();

        return isset($registry[$username]) ? count($registry[$username]) : 0;
    }

    private static function cleanupExpiredSessions(): void
    {
        $registry = self::readRegistry();
        $now = time();
        $updated = [];

        foreach ($registry as $username => $sessions) {
            foreach ($sessions as $sessionId => $info) {
                $lastActivity = (int) ($info['last_activity'] ?? 0);
                if (($now - $lastActivity) <= SESSION_TIMEOUT_SECONDS) {
                    $updated[$username][$sessionId] = $info;
                    continue;
                }

                self::deleteSessionFile($sessionId);
            }
        }

        self::writeRegistry($updated);
    }

    private static function touchRegistryEntry(string $username, string $sessionId): void
    {
        $registry = self::readRegistry();

        if (!isset($registry[$username][$sessionId])) {
            $registry[$username][$sessionId] = [
                'created_at' => time(),
            ];
        }

        $registry[$username][$sessionId]['last_activity'] = time();
        self::writeRegistry($registry);
    }

    private static function removeSession(string $username, string $sessionId): void
    {
        $registry = self::readRegistry();

        unset($registry[$username][$sessionId]);

        if (isset($registry[$username]) && count($registry[$username]) === 0) {
            unset($registry[$username]);
        }

        self::writeRegistry($registry);
        self::deleteSessionFile($sessionId);
    }

    private static function deleteSessionFile(string $sessionId): void
    {
        $sessionFile = APP_SESSION_DIR . DIRECTORY_SEPARATOR . 'sess_' . $sessionId;
        if (file_exists($sessionFile)) {
            unlink($sessionFile);
        }
    }

    private static function readRegistry(): array
    {
        $handle = fopen(SESSION_REGISTRY_FILE, 'c+');
        if ($handle === false) {
            return [];
        }

        flock($handle, LOCK_SH);
        $contents = stream_get_contents($handle);
        flock($handle, LOCK_UN);
        fclose($handle);

        if ($contents === false || trim($contents) === '') {
            return [];
        }

        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function writeRegistry(array $registry): void
    {
        $handle = fopen(SESSION_REGISTRY_FILE, 'c+');
        if ($handle === false) {
            return;
        }

        flock($handle, LOCK_EX);
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, json_encode($registry, JSON_PRETTY_PRINT));
        fflush($handle);
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    private static function forceLogout(): void
    {
        self::logoutCurrentSession();
        header('Location: index.php');
        exit;
    }
}

