<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
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

            <p class="lead">Simple PHP and MySQL attendance system</p>

            <div class="grid">
                <a class="panel" href="student_register.php">
                    <h2>Student Registration</h2>
                    <p>Students can register with roll number, name, email, course and semester.</p>
                </a>

                <a class="panel" href="teacher_attendance.php">
                    <h2>Teacher Attendance</h2>
                    <p>Teacher can mark attendance online using checkboxes with roll number and name.</p>
                </a>

                <a class="panel" href="view_attendance.php">
                    <h2>Attendance Report</h2>
                    <p>View attendance records date-wise for all students.</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
