<?php
require_once "db.php";

if (!isset($_GET["id"])) {
    header("Location: index.php?message=Student not found");
    exit;
}

$studentId = (int) $_GET["id"];
$deleteQuery = "DELETE FROM students WHERE id = $studentId";

if (mysqli_query($conn, $deleteQuery)) {
    header("Location: index.php?message=Student record deleted successfully");
    exit;
}

header("Location: index.php?message=Unable to delete student record");
exit;
?>
