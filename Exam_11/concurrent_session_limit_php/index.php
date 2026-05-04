<?php
session_start();

$maxSessions = 3;
$timeout = 300;
$dataFolder = __DIR__ . '/data';
$dataFile = $dataFolder . '/session_data.json';

if (!is_dir($dataFolder)) {
    mkdir($dataFolder, 0777, true);
}

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

function readSessions($file)
{
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function saveSessions($file, $data)
{
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function removeExpiredSessions($data, $timeout)
{
    $now = time();

    foreach ($data as $username => $sessions) {
        foreach ($sessions as $sessionId => $lastActivity) {
            if (($now - $lastActivity) > $timeout) {
                unset($data[$username][$sessionId]);
            }
        }

        if (empty($data[$username])) {
            unset($data[$username]);
        }
    }

    return $data;
}

$allSessions = readSessions($dataFile);
$allSessions = removeExpiredSessions($allSessions, $timeout);
saveSessions($dataFile, $allSessions);

$message = '';

if (isset($_GET['logout'])) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sessionId = session_id();
        unset($allSessions[$username][$sessionId]);

        if (empty($allSessions[$username])) {
            unset($allSessions[$username]);
        }

        saveSessions($dataFile, $allSessions);
    }

    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sessionId = session_id();

    if ((time() - $_SESSION['last_activity']) > $timeout) {
        unset($allSessions[$username][$sessionId]);

        if (empty($allSessions[$username])) {
            unset($allSessions[$username]);
        }

        saveSessions($dataFile, $allSessions);
        session_destroy();
        header('Location: index.php?msg=Session expired after 5 minutes');
        exit;
    }

    $_SESSION['last_activity'] = time();
    $allSessions[$username][$sessionId] = time();
    saveSessions($dataFile, $allSessions);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $message = 'Please enter username';
    } else {
        $userSessions = isset($allSessions[$username]) ? $allSessions[$username] : [];

        if (!isset($userSessions[session_id()]) && count($userSessions) >= $maxSessions) {
            $message = 'Only 3 concurrent sessions are allowed for this user';
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['last_activity'] = time();
            $allSessions[$username][session_id()] = time();
            saveSessions($dataFile, $allSessions);
            header('Location: index.php');
            exit;
        }
    }
}

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Concurrent Session Limit</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f7fb; }
        .box { width: 420px; margin: 60px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input, button { width: 100%; padding: 10px; margin-top: 10px; box-sizing: border-box; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="box">
        <h2>PHP Session Control</h2>

        <?php if (!isset($_SESSION['username'])): ?>
            <p>Maximum 3 concurrent sessions per user. Session timeout is 5 minutes.</p>
            <form method="post">
                <input type="text" name="username" placeholder="Enter username">
                <button type="submit">Login</button>
            </form>
        <?php else: ?>
            <p class="success">Welcome, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
            <p>Current active sessions for this user:
                <b><?php echo count($allSessions[$_SESSION['username']]); ?></b>
            </p>
            <p><a href="index.php?logout=1">Logout</a></p>
        <?php endif; ?>

        <?php if ($message !== ''): ?>
            <p class="<?php echo strpos($message, 'expired') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
