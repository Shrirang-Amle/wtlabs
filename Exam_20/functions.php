<?php
function resetGame()
{
    $_SESSION["board"] = array("", "", "", "", "", "", "", "", "");
    $_SESSION["player"] = "X";
    $_SESSION["message"] = "Player X turn";
    $_SESSION["game_over"] = false;
}

function checkWinner($board)
{
    $winPatterns = array(
        array(0, 1, 2),
        array(3, 4, 5),
        array(6, 7, 8),
        array(0, 3, 6),
        array(1, 4, 7),
        array(2, 5, 8),
        array(0, 4, 8),
        array(2, 4, 6)
    );

    foreach ($winPatterns as $pattern) {
        $a = $pattern[0];
        $b = $pattern[1];
        $c = $pattern[2];

        if ($board[$a] != "" && $board[$a] == $board[$b] && $board[$b] == $board[$c]) {
            return $board[$a];
        }
    }

    return "";
}

function isBoardFull($board)
{
    foreach ($board as $cell) {
        if ($cell == "") {
            return false;
        }
    }

    return true;
}
?>
