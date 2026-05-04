<?php
require_once "db.php";

if (!isset($_GET["id"]) && !isset($_POST["id"])) {
    header("Location: index.php?message=Student not found");
    exit;
}

$studentId = isset($_GET["id"]) ? (int) $_GET["id"] : (int) $_POST["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST["name"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $course = mysqli_real_escape_string($conn, trim($_POST["course"]));
    $phone = mysqli_real_escape_string($conn, trim($_POST["phone"]));

    $updateQuery = "
        UPDATE students
        SET name = '$name', email = '$email', course = '$course', phone = '$phone'
        WHERE id = $studentId
    ";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: index.php?message=Student record updated successfully");
        exit;
    }

    $error = "Unable to update student record.";
}

$studentQuery = mysqli_query($conn, "SELECT id, name, email, course, phone FROM students WHERE id = $studentId");
$student = mysqli_fetch_assoc($studentQuery);

if (!$student) {
    header("Location: index.php?message=Student not found");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="page">
        <div class="container narrow">
            <div class="hero small">
                <div>
                    <p class="eyebrow">Update Student</p>
                    <h1>Edit Record</h1>
                    <p class="subtitle">Make changes and save the updated student details.</p>
                </div>
                <a class="btn btn-back" href="index.php">Back</a>
            </div>

            <div class="card form-card">
                <?php if (isset($error)): ?>
                    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="edit.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student["id"]); ?>">

                    <div class="form-grid">
                        <div class="input-group">
                            <label for="name">Student Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student["name"]); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student["email"]); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($student["course"]); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student["phone"]); ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-save">Update Student</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
