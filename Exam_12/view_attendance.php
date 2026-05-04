<?php
require_once __DIR__ . '/config.php';

$selectedDate = $_GET['attendance_date'] ?? '';
$records = [];

$sql = 'SELECT a.attendance_date, s.roll_no, s.name, s.course, s.semester, a.status
        FROM attendance a
        INNER JOIN students s ON s.id = a.student_id';

if ($selectedDate !== '') {
    $sql .= ' WHERE a.attendance_date = ?';
}

$sql .= ' ORDER BY a.attendance_date DESC, s.roll_no ASC';

if ($selectedDate !== '') {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Attendance System</h1>
            <div class="nav">
                <a href="index.php">Home</a>
                <a href="student_register.php">Student Register</a>
                <a href="teacher_attendance.php">Take Attendance</a>
                <a href="view_attendance.php">View Attendance</a>
            </div>

            <h2>Attendance Report</h2>

            <form method="get" class="inline-form">
                <label>Filter By Date</label>
                <input type="date" name="attendance_date" value="<?php echo h($selectedDate); ?>">
                <button type="submit">Search</button>
                <a class="button-link" href="view_attendance.php">Reset</a>
            </form>

            <?php if (empty($records)): ?>
                <div class="message error">No attendance records found.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo h($record['attendance_date']); ?></td>
                                <td><?php echo h($record['roll_no']); ?></td>
                                <td><?php echo h($record['name']); ?></td>
                                <td><?php echo h($record['course']); ?></td>
                                <td><?php echo h($record['semester']); ?></td>
                                <td class="<?php echo $record['status'] === 'Present' ? 'present' : 'absent'; ?>">
                                    <?php echo h($record['status']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
