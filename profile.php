<?php
// profile.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $conn->prepare("SELECT username, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get game session stats
$sql = "SELECT COUNT(*) as total_games, 
               SUM(generations) as total_generations,
               MAX(generations) as max_generations,
               AVG(generations) as avg_generations,
               MAX(max_population) as highest_population
        FROM game_sessions 
        WHERE user_id = $user_id";
$result = $conn->query($sql);
$stats = $result->fetch_assoc();

// Get recent game sessions
$sql = "SELECT id, start_time, end_time, generations, max_population 
        FROM game_sessions 
        WHERE user_id = $user_id 
        ORDER BY start_time DESC 
        LIMIT 5";
$result = $conn->query($sql);
$recent_sessions = [];
while ($row = $result->fetch_assoc()) {
    $recent_sessions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User Profile</title>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .stat-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #04AA6D;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #04AA6D;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo $user['username']; ?>!</p>
        <p>Account created: <?php echo $user['created_at']; ?></p>
        
        <h2>Your Game Statistics</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <p>Total Games</p>
                <div class="stat-value"><?php echo $stats['total_games']; ?></div>
            </div>
            <div class="stat-box">
                <p>Total Generations</p>
                <div class="stat-value"><?php echo $stats['total_generations'] ?? 0; ?></div>
            </div>
            <div class="stat-box">
                <p>Highest Generation</p>
                <div class="stat-value"><?php echo $stats['max_generations'] ?? 0; ?></div>
            </div>
            <div class="stat-box">
                <p>Highest Population</p>
                <div class="stat-value"><?php echo $stats['highest_population'] ?? 0; ?></div>
            </div>
        </div>
        
        <h2>Recent Game Sessions</h2>
        <?php if (count($recent_sessions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Generations</th>
                        <th>Max Population</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_sessions as $session): ?>
                        <tr>
                            <td><?php echo $session['start_time']; ?></td>
                            <td><?php echo $session['end_time'] ?? 'In Progress'; ?></td>
                            <td><?php echo $session['generations']; ?></td>
                            <td><?php echo $session['max_population']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No game sessions yet. Start playing to see your statistics!</p>
        <?php endif; ?>
        
        <div class="button-container" style="margin-top: 20px; text-align: center;">
            <a href="game.php"><button>Back to Game</button></a>
            <a href="logout.php"><button>Log Out</button></a>
        </div>
    </div>
</body>
</html>