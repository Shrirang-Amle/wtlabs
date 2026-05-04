<?php
include 'db.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM students WHERE id=$id");
$row = $result->fetch_assoc();
?>

<form action="update_process.php" method="post">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

Email: <input type="text" name="email" value="<?php echo $row['email']; ?>"><br><br>

<input type="submit" value="Update">

</form>