<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$conn = new mysqli("localhost", "root", "", "vit_results");

if ($conn->connect_error) {
    die("Connection failed");
}
$rawData = file_get_contents("php://input");

file_put_contents("debug.txt", $rawData);

$data = json_decode($rawData, true);
if (!$data) {
    echo json_encode([
        "error" => "No JSON data received",
        "raw" => $rawData
    ]);
    exit();
}

$name = $data['name'] ?? '';
$course = $data['course'] ?? '';

$sub1_mse = $data['sub1_mse'] ?? 0;
$sub1_ese = $data['sub1_ese'] ?? 0;
$sub2_mse = $data['sub2_mse'] ?? 0;
$sub2_ese = $data['sub2_ese'] ?? 0;
$sub3_mse = $data['sub3_mse'] ?? 0;
$sub3_ese = $data['sub3_ese'] ?? 0;
$sub4_mse = $data['sub4_mse'] ?? 0;
$sub4_ese = $data['sub4_ese'] ?? 0;

// Insert query
$sql = "INSERT INTO students 
(name, course, sub1_mse, sub1_ese, sub2_mse, sub2_ese, sub3_mse, sub3_ese, sub4_mse, sub4_ese)
VALUES 
('$name','$course','$sub1_mse','$sub1_ese','$sub2_mse','$sub2_ese','$sub3_mse','$sub3_ese','$sub4_mse','$sub4_ese')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>