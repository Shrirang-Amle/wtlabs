<?php
include 'data.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Seats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-box">
        <h1>Seating Arrangement</h1>

        <?php
        for ($i = 1; $i <= $rows; $i++) {
            echo "<div class='seat-row'>";

            for ($j = 0; $j < count($cols); $j++) {
                $seatNo = $i . $cols[$j];

                if (isset($_SESSION['booked_seats'][$seatNo])) {
                    echo "<span class='seat booked'>$seatNo</span>";
                } else {
                    echo "<span class='seat available'>$seatNo</span>";
                }

                if ($j == 1) {
                    echo "<span class='aisle'></span>";
                }
            }

            echo "</div>";
        }
        ?>

        <h2>Booked Seats</h2>

        <div class="list">
            <?php
            if (count($_SESSION['booked_seats']) == 0) {
                echo "No seats booked.";
            } else {
                foreach ($_SESSION['booked_seats'] as $seat => $name) {
                    echo "<p>$seat - $name</p>";
                }
            }
            ?>
        </div>

        <p style="text-align:center;">
            <a href="book_seat.php">Go Back</a>
        </p>
    </div>
</body>
</html>
