<?php
require_once "db.php";

$students = mysqli_query($conn, "SELECT id, name, email, course, phone FROM students ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="page">
        <div class="container">
            <div class="hero">
                <div>
                    <p class="eyebrow">Simple PHP Student Manager</p>
                    <h1>Student Records</h1>
                    <p class="subtitle">View, edit, and delete student records from your database with a clean responsive layout.</p>
                </div>
            </div>

            <?php if (isset($_GET["message"])): ?>
                <div class="alert success">
                    <?php echo htmlspecialchars($_GET["message"]); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h2>All Students</h2>
                    <span class="badge">Total: <?php echo mysqli_num_rows($students); ?></span>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($students && mysqli_num_rows($students) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($students)): ?>
                                    <tr>
                                        <td data-label="ID"><?php echo htmlspecialchars($row["id"]); ?></td>
                                        <td data-label="Name"><?php echo htmlspecialchars($row["name"]); ?></td>
                                        <td data-label="Email"><?php echo htmlspecialchars($row["email"]); ?></td>
                                        <td data-label="Course"><?php echo htmlspecialchars($row["course"]); ?></td>
                                        <td data-label="Phone"><?php echo htmlspecialchars($row["phone"]); ?></td>
                                        <td data-label="Action">
                                            <div class="actions">
                                                <a class="btn btn-edit" href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a>
                                                <a class="btn btn-delete" href="delete.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-state">No student records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
