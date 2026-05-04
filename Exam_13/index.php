<?php
declare(strict_types=1);

require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .box {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        a {
            color: #0b63ce;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Welcome, <?php echo htmlspecialchars($userName); ?></h2>
        <p>You are logged in successfully.</p>
        <p><strong>Cookie User:</strong> <?php echo isset($_COOKIE['remember_user']) ? htmlspecialchars($_COOKIE['remember_user']) : 'Not set'; ?></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
