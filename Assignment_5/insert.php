<?php

include "db.php";

$name = $_POST['name'];
$email = $_POST['email'];

$sql = "INSERT INTO students(name,email) VALUES('$name','$email')";

if($conn->query($sql)){
    echo "\nRecord inserted successfully";
}
else{
    echo "Error: ".$conn->error;
}

$conn->close();

?>