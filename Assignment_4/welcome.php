<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "Please login first.";
    exit();
}

echo "<h2>Welcome " . $_SESSION['username'] . "</h2>";

if(isset($_COOKIE['username'])){
    echo "Cookie Stored Username: " . $_COOKIE['username'];
}

echo "<br><br><a href='logout.php'>Logout</a>";
?>