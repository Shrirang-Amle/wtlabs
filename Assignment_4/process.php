<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid Email Format";
        exit();
    }

    
    setcookie("username", $name, time()+3600, "/");

   
    $_SESSION['username'] = $name;

    echo "<h3>Form Submitted Successfully</h3>";
    echo "Name: " . $name . "<br>";
    echo "Email: " . $email . "<br>";

    echo "<br><a href='welcome.php'>Go to Welcome Page</a>";
}
?>