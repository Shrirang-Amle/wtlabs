<?php

$host = "localhost";
$user = "root";
$password = "";

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dbname = "student_db";
$result = $conn->query("SHOW DATABASES LIKE '$dbname'");


if ($result->num_rows == 0) {

    $sql = "CREATE DATABASE $dbname";

    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database";
    }

} else {
    echo "Database already exists";
}
$conn->select_db("student_db");

?>