<?php
require_once __DIR__ . '/config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rollNo = trim($_POST['roll_no'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $semester = trim($_POST['semester'] ?? '');

    if ($rollNo === '' || $name === '' || $email === '' || $course === '' || $semester === '') {
        $message = 'Please fill all fields.';
        $messageType = 'error';
    } else {
        $check = $conn->prepare('SELECT id FROM students WHERE roll_no = ? OR email = ?');
        $check->bind_param('ss', $rollNo, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = 'Roll number or email already registered.';
            $messageType = 'error';
        } else {
            $stmt = $conn->prepare('INSERT INTO students (roll_no, name, email, course, semester) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $rollNo, $name, $email, $course, $semester);

            if ($stmt->execute()) {
                $message = 'Student registration successful.';
                $messageType = 'success';
                $_POST = [];
            } else {
                $message = 'Unable to register student.';
                $messageType = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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

            <h2>Student Registration</h2>

            <?php if ($message !== ''): ?>
                <div class="message <?php echo h($messageType); ?>"><?php echo h($message); ?></div>
            <?php endif; ?>

            <form method="post">
                <label>Roll Number</label>
                <input type="text" name="roll_no" value="<?php echo h($_POST['roll_no'] ?? ''); ?>" required>

                <label>Student Name</label>
                <input type="text" name="name" value="<?php echo h($_POST['name'] ?? ''); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo h($_POST['email'] ?? ''); ?>" required>

                <label>Course</label>
                <input type="text" name="course" value="<?php echo h($_POST['course'] ?? ''); ?>" required>

                <label>Semester</label>
                <input type="text" name="semester" value="<?php echo h($_POST['semester'] ?? ''); ?>" required>

                <button type="submit">Register Student</button>
            </form>
        </div>
    </div>
</body>
</html>
