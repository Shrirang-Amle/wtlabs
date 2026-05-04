<?php
require_once __DIR__ . '/config.php';

$message = '';
$messageType = '';
$selectedDate = $_POST['attendance_date'] ?? date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_attendance'])) {
    $selectedDate = trim($_POST['attendance_date'] ?? '');
    $attendanceData = $_POST['status'] ?? [];

    if ($selectedDate === '') {
        $message = 'Please select attendance date.';
        $messageType = 'error';
    } else {
        $deleteStmt = $conn->prepare('DELETE FROM attendance WHERE attendance_date = ?');
        $deleteStmt->bind_param('s', $selectedDate);
        $deleteStmt->execute();

        $insertStmt = $conn->prepare('INSERT INTO attendance (student_id, attendance_date, status) VALUES (?, ?, ?)');

        foreach ($attendanceData as $studentId => $status) {
            $studentId = (int) $studentId;
            $status = $status === 'Present' ? 'Present' : 'Absent';
            $insertStmt->bind_param('iss', $studentId, $selectedDate, $status);
            $insertStmt->execute();
        }

        $message = 'Attendance saved successfully for ' . $selectedDate . '.';
        $messageType = 'success';
    }
}

$students = [];
$result = $conn->query('SELECT id, roll_no, name, course, semester FROM students ORDER BY roll_no ASC');

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$savedAttendance = [];
if ($selectedDate !== '') {
    $attendanceStmt = $conn->prepare('SELECT student_id, status FROM attendance WHERE attendance_date = ?');
    $attendanceStmt->bind_param('s', $selectedDate);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();

    while ($row = $attendanceResult->fetch_assoc()) {
        $savedAttendance[$row['student_id']] = $row['status'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Attendance</title>
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

            <h2>Teacher Attendance Panel</h2>

            <?php if ($message !== ''): ?>
                <div class="message <?php echo h($messageType); ?>"><?php echo h($message); ?></div>
            <?php endif; ?>

            <?php if (empty($students)): ?>
                <div class="message error">No students found. Please register students first.</div>
            <?php else: ?>
                <form method="post">
                    <label>Attendance Date</label>
                    <input type="date" name="attendance_date" value="<?php echo h($selectedDate); ?>" required>

                    <table>
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Semester</th>
                                <th>Present</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <?php $isPresent = ($savedAttendance[$student['id']] ?? 'Absent') === 'Present'; ?>
                                <tr>
                                    <td><?php echo h($student['roll_no']); ?></td>
                                    <td><?php echo h($student['name']); ?></td>
                                    <td><?php echo h($student['course']); ?></td>
                                    <td><?php echo h($student['semester']); ?></td>
                                    <td>
                                        <input type="hidden" name="status[<?php echo (int) $student['id']; ?>]" value="Absent">
                                        <input type="checkbox" name="status[<?php echo (int) $student['id']; ?>]" value="Present" <?php echo $isPresent ? 'checked' : ''; ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" name="save_attendance">Save Attendance</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
