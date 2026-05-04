<?php
$conn = new mysqli("localhost", "root", "", "vit_results");

if ($conn->connect_error) {
    die("Connection failed");
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>