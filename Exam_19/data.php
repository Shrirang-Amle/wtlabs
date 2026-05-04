<?php
session_start();

$rows = 6;
$cols = array('A', 'B', 'C', 'D');

if (!isset($_SESSION['booked_seats'])) {
    $_SESSION['booked_seats'] = array();
}
?>
