<?php

declare(strict_types=1);

require_once __DIR__ . '/SessionManager.php';

SessionManager::validateAuthenticatedSession();

$username = (string) $_SESSION['username'];
$activeSessionCount = SessionManager::getActiveSessionCount($username);
$remainingSeconds = SESSION_TIMEOUT_SECONDS - (time() - (int) $_SESSION['last_activity']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #eef7ff, #ffffff);
            min-height: 100vh;
            display: grid;
            place-items: center;
        }
        .panel {
            width: min(700px, 94vw);
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 18px 45px rgba(10, 46, 78, 0.12);
        }
        h1 {
            margin-top: 0;
            color: #10304a;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin: 22px 0;
        }
        .stat {
            background: #f5fbff;
            padding: 16px;
            border-radius: 14px;
            border: 1px solid #d7e9f5;
        }
        .label {
            display: block;
            color: #48667d;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .value {
            font-size: 24px;
            font-weight: bold;
            color: #0e6aa9;
        }
        a.button {
            display: inline-block;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 10px;
            background: #0f6db2;
            color: #fff;
        }
        p {
            color: #446178;
        }
    </style>
</head>
<body>
    <div class="panel">
        <h1>Welcome, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Your session stays active while you keep using the application. If there is no activity for 5 minutes, the session expires automatically.</p>

        <div class="grid">
            <div class="stat">
                <span class="label">Maximum Allowed Sessions</span>
                <span class="value"><?= MAX_CONCURRENT_SESSIONS ?></span>
            </div>
            <div class="stat">
                <span class="label">Current Active Sessions</span>
                <span class="value"><?= $activeSessionCount ?></span>
            </div>
            <div class="stat">
                <span class="label">Timeout</span>
                <span class="value"><?= SESSION_TIMEOUT_SECONDS / 60 ?> min</span>
            </div>
        </div>

        <p>Time since your last activity is refreshed on every request. Remaining idle time right now: <strong><?= max(0, $remainingSeconds) ?> seconds</strong>.</p>
        <a class="button" href="logout.php">Logout</a>
    </div>
</body>
</html>

