<?php
require_once 'config.php';

if (!isStudentLoggedIn()) {
    redirect('student_login.php');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $complaint = trim($_POST['complaint'] ?? '');

    if ($subject === '' || $department === '' || $complaint === '') {
        $error = 'Please fill in all fields before submitting the complaint.';
    } else {
        $stmt = $conn->prepare(
            'INSERT INTO complaints (student_id, subject, department, complaint_text) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('isss', $_SESSION['student_id'], $subject, $department, $complaint);

        if ($stmt->execute()) {
            $message = 'Complaint submitted successfully.';
        } else {
            $error = 'Unable to submit complaint right now.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Complaint</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="topbar">
                <div>
                    <h2>Complaint Registration</h2>
                    <p class="muted">Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?></p>
                </div>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>

            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div>
                    <label for="subject">Complaint Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div>
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="Academics">Academics</option>
                        <option value="Hostel">Hostel</option>
                        <option value="Transport">Transport</option>
                        <option value="Library">Library</option>
                        <option value="Examination">Examination</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="complaint">Complaint Details</label>
                    <textarea id="complaint" name="complaint" required></textarea>
                </div>
                <button class="btn" type="submit">Submit Complaint</button>
            </form>
        </div>
    </div>
</body>
</html>
