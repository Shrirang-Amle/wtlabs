<?php
include 'db.php';

$sql = "SELECT * FROM students";
$result = $conn->query($sql);

echo "<h2>Student Records</h2>";

echo "<table border='1'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Update</th>
<th>Delete</th>
</tr>";

while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td><a href='update.php?id=".$row['id']."'>Update</a></td>";
    echo "<td><a href='delete.php?id=".$row['id']."'>Delete</a></td>";
    echo "</tr>";
}

echo "</table>";

$conn->close();
?>