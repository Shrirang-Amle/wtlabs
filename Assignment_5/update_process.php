<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];

$sql = "UPDATE students SET name='$name', email='$email' WHERE id=$id";

if($conn->query($sql) === TRUE){
    echo "Record updated successfully";
}
else{
    echo "Error updating record";
}

$conn->close();

header("Location: display.php");
?>