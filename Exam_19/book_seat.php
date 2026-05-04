<?php
include 'data.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $name = trim($_POST['name']);
    $seat = $_POST['seat'];

    if ($name == '' || $seat == '') {
        $message = 'Please fill all fields.';
    } elseif (isset($_SESSION['booked_seats'][$seat])) {
        $message = 'Seat already booked.';
    } else {
        $_SESSION['booked_seats'][$seat] = $name;
        $message = 'Seat booked successfully.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Seat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-box">
        <h1>Airplane Seat Booking</h1>

        <?php if ($message != '') { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <form method="post">
            <input type="text" name="name" placeholder="Enter passenger name">
            <select name="seat">
                <option value="">Select Seat</option>
                <?php
                for ($i = 1; $i <= $rows; $i++) {
                    for ($j = 0; $j < count($cols); $j++) {
                        $seatNo = $i . $cols[$j];
                        if (!isset($_SESSION['booked_seats'][$seatNo])) {
                            echo "<option value='$seatNo'>$seatNo</option>";
                        }
                    }
                }
                ?>
            </select>
            <button type="submit" name="book">Book</button>
        </form>

        <form action="clear.php" method="post">
            <button type="submit" class="clear-btn">Clear All Bookings</button>
        </form>

        <p style="text-align:center;">
            <a href="view_seats.php">View Seating Arrangement</a>
        </p>
    </div>
</body>
</html>
