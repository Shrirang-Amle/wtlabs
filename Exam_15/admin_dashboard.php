<?php
require_once 'config.php';

if (!isAdminLoggedIn()) {
    redirect('admin_login.php');
}

$sql = 'SELECT complaints.id, students.name, students.email, complaints.subject, complaints.department,
        complaints.complaint_text, complaints.created_at
        FROM complaints
        INNER JOIN students ON complaints.student_id = students.id
        ORDER BY complaints.created_at DESC';

$complaints = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="topbar">
                <div>
                    <h2>All Complaints</h2>
                    <p class="muted">Logged in as <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
                </div>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Department</th>
                        <th>Complaint</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($complaints && $complaints->num_rows > 0): ?>
                        <?php while ($row = $complaints->fetch_assoc()): ?>
                            <tr>
                                <td data-label="ID"><?php echo (int) $row['id']; ?></td>
                                <td data-label="Student">
                                    <?php echo htmlspecialchars($row['name']); ?><br>
                                    <span class="muted"><?php echo htmlspecialchars($row['email']); ?></span>
                                </td>
                                <td data-label="Subject"><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                                <td data-label="Complaint"><?php echo nl2br(htmlspecialchars($row['complaint_text'])); ?></td>
                                <td data-label="Date"><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No complaints have been submitted yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
