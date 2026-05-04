<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Bill Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Electricity Bill Calculator</h2>
    <form method="post">
        <label>Enter Units Consumed:</label>
        <input type="number" name="units" required>

        <button type="submit" name="submit">Calculate</button>
    </form>

    <?php
    if(isset($_POST['submit'])) {
        $units = $_POST['units'];
        $bill = 0;

        if($units <= 50) {
            $bill = $units * 3.50;
        }
        elseif($units <= 150) {
            $bill = (50 * 3.50) + (($units - 50) * 4.00);
        }
        elseif($units <= 250) {
            $bill = (50 * 3.50) + (100 * 4.00) + (($units - 150) * 5.20);
        }
        else {
            $bill = (50 * 3.50) + (100 * 4.00) + (100 * 5.20) + (($units - 250) * 6.50);
        }

        echo "<div class='result'>";
        echo "<h3>Total Bill: ₹ " . number_format($bill, 2) . "</h3>";
        echo "</div>";
    }
    ?>

</div>

</body>
</html>