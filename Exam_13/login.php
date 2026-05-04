<?php
declare(strict_types=1);

require_once 'config.php';
require_once 'functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';
$savedUser = $_COOKIE['remember_user'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Email and password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            setUserCookie($user['name']);
            header('Location: index.php');
            exit;
        }

        $message = 'Invalid login details.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 30px;
        }
        .form-box {
            max-width: 420px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .msg {
            margin-bottom: 15px;
            color: #c0392b;
        }
        a {
            color: #0b63ce;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>User Login</h2>
        <?php if ($message !== ''): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p><strong>Last Cookie User:</strong> <?php echo $savedUser !== '' ? htmlspecialchars($savedUser) : 'No cookie found'; ?></p>
        <p>New user? <a href="register.php">Create account</a></p>
    </div>
</body>
</html>
