<?php 
session_start(); 
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Start a game session when the page loads
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO game_sessions (user_id) VALUES (?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$game_session_id = $conn->insert_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>Conway's Game of Life</title>
    <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body>
    <div class="game-wrapper">
        <h1>Conway's Game of Life</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        
        <div class="stats">
            <p>Generation: <span id="generation-count">0</span></p>
            <p>Population: <span id="population-count">0</span></p>
        </div>
        
        <div class="grid-size-control">
            <label for="grid-size">Grid Size:</label>
            <select id="grid-size" onchange="changeGridSize()">
                <option value="20">20x20</option>
                <option value="30">30x30</option>
                <option value="40">40x40</option>
            </select>
        </div>
        
        <div id="grid-container"></div>
        
        <div class="pattern-selector">
            <label for="pattern-select">Load Pattern:</label>
            <select id="pattern-select">
                <option value="">Select a pattern</option>
                <optgroup label="Still Life">
                    <option value="block">Block</option>
                    <option value="beehive">Beehive</option>
                    <option value="loaf">Loaf</option>
                </optgroup>
                <optgroup label="Oscillators">
                    <option value="blinker">Blinker</option>
                    <option value="beacon">Beacon</option>
                    <option value="toad">Toad</option>
                </optgroup>
                <optgroup label="Spaceships">
                    <option value="glider">Glider</option>
                </optgroup>
            </select>
            <button id="load-pattern-btn">Load Pattern</button>
        </div>
        
        <div class="controls">
            <button id="start-btn">Start</button>
            <button id="stop-btn">Stop</button>
            <button id="next-btn">Next</button>
            <button id="jump-btn">+23 Generations</button>
            <button id="reset-btn">Reset</button>
        </div>
        
        <!-- Only include this once -->
        <div id="react-component"></div>
        
        <div class="user-controls">
            <a href="profile.php"><button>Profile</button></a>
            <a href="about.php"><button>About</button></a>
            <a href="logout.php"><button>Log Out</button></a>
        </div>
        
        <input type="hidden" id="game-session-id" value="<?php echo $game_session_id; ?>">
    </div>

    <script src="script.js"></script>
    <script src="patterns.js"></script>
    <script type="text/babel" src="react-component.js"></script>
</body>
</html>