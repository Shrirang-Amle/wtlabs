<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "game.php"; ?>

    <div class="game-box">
        <h1>Tic-Tac-Toe</h1>
        <div class="message"><?php echo $message; ?></div>

        <form method="post">
            <div class="board">
                <?php for ($i = 0; $i < 9; $i++) { ?>
                    <button
                        type="submit"
                        name="cell"
                        value="<?php echo $i; ?>"
                        class="cell-btn"
                        <?php if ($board[$i] != "" || $gameOver == true) { echo "disabled"; } ?>
                    >
                        <?php echo $board[$i]; ?>
                    </button>
                <?php } ?>
            </div>

            <button type="submit" name="reset" class="reset-btn">Restart Game</button>
        </form>
    </div>
</body>
</html>
