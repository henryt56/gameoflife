<?php 
session_start(); 
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>Game</title>
</head>
<body>
    <div class="game-wrapper">
        <div id="grid-container"></div>
        <div class="controls">
            <button id="next-btn">Next</button>
            <button id="jump-btn">23x Next</button>
            <button id="start-btn">Start</button>
            <button id="stop-btn">Stop</button>
        </div>
        <br>
        <a href="logout.php"><button>Log Out</button></a>
    </div>

    <script src="script.js"></script>
</body>
</html>
