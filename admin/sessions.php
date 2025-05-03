<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

// Get game sessions with user info
$sql = "SELECT gs.id, gs.user_id, u.username, gs.start_time, gs.end_time, gs.generations, gs.max_population 
        FROM game_sessions gs
        JOIN users u ON gs.user_id = u.id
        ORDER BY gs.start_time DESC";
$result = $conn->query($sql);
$sessions = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>Game Sessions</title>
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #04AA6D;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-nav">
            <h1>Game Sessions</h1>
            <div>
                <a href="dashboard.php"><button>Dashboard</button></a>
                <a href="../logout.php"><button>Log Out</button></a>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Generations</th>
                    <th>Max Population</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                    <?php 
                    $duration = '';
                    if (!empty($session['end_time'])) {
                        $start = new DateTime($session['start_time']);
                        $end = new DateTime($session['end_time']);
                        $diff = $start->diff($end);
                        $duration = $diff->format('%H:%I:%S');
                    } else {
                        $duration = 'In progress';
                    }
                    ?>
                    <tr>
                        <td><?php echo $session['id']; ?></td>
                        <td><?php echo $session['username']; ?></td>
                        <td><?php echo $session['start_time']; ?></td>
                        <td><?php echo $session['end_time'] ?? 'In progress'; ?></td>
                        <td><?php echo $duration; ?></td>
                        <td><?php echo $session['generations']; ?></td>
                        <td><?php echo $session['max_population']; ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($sessions)): ?>
                    <tr>
                        <td colspan="7">No game sessions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>