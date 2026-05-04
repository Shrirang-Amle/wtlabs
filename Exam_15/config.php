<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'college_complaints';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

function isStudentLoggedIn(): bool
{
    return isset($_SESSION['student_id']);
}

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id']);
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}
?>
