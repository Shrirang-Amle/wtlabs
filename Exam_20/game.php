<?php
session_start();

include "functions.php";

if (!isset($_SESSION["board"])) {
    resetGame();
}

if (isset($_POST["reset"])) {
    resetGame();
}

if (isset($_POST["cell"]) && $_SESSION["game_over"] == false) {
    $cell = (int) $_POST["cell"];

    if ($_SESSION["board"][$cell] == "") {
        $_SESSION["board"][$cell] = $_SESSION["player"];

        $winner = checkWinner($_SESSION["board"]);

        if ($winner != "") {
            $_SESSION["message"] = "Player " . $winner . " wins!";
            $_SESSION["game_over"] = true;
        } elseif (isBoardFull($_SESSION["board"])) {
            $_SESSION["message"] = "Match draw!";
            $_SESSION["game_over"] = true;
        } else {
            if ($_SESSION["player"] == "X") {
                $_SESSION["player"] = "O";
            } else {
                $_SESSION["player"] = "X";
            }

            $_SESSION["message"] = "Player " . $_SESSION["player"] . " turn";
        }
    }
}

$board = $_SESSION["board"];
$message = $_SESSION["message"];
$gameOver = $_SESSION["game_over"];
?>
