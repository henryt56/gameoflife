<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

// Get user statistics
$sql = "SELECT COUNT(*) as total_users FROM users";
$result = $conn->query($sql);
$totalUsers = $result->fetch_assoc()['total_users'];

// Get game session statistics
$sql = "SELECT COUNT(*) as total_sessions, AVG(generations) as avg_generations, MAX(generations) as max_generations FROM game_sessions";
$result = $conn->query($sql);
$gameStats = $result->fetch_assoc();

// Get recent users
$sql = "SELECT id, username, created_at FROM users ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($sql);
$recentUsers = [];
while ($row = $result->fetch_assoc()) {
    $recentUsers[] = $row;
}

// Get recent game sessions
$sql = "SELECT gs.id, u.username, gs.start_time, gs.end_time, gs.generations, gs.max_population 
        FROM game_sessions gs
        JOIN users u ON gs.user_id = u.id
        ORDER BY gs.start_time DESC LIMIT 10";
$result = $conn->query($sql);
$recentSessions = [];
while ($row = $result->fetch_assoc()) {
    $recentSessions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>Admin Dashboard</title>
    <style>
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #04AA6D;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #04AA6D;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="admin-nav">
            <h1>Admin Dashboard</h1>
            <div>
                <a href="users.php"><button>Manage Users</button></a>
                <a href="sessions.php"><button>View All Sessions</button></a>
                <a href="../logout.php"><button>Log Out</button></a>
            </div>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-value"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Game Sessions</h3>
                <div class="stat-value"><?php echo $gameStats['total_sessions']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Avg. Generations</h3>
                <div class="stat-value"><?php echo round($gameStats['avg_generations']); ?></div>
            </div>
        </div>
        
        <h2>Recent Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentUsers as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> | 
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Recent Game Sessions</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Generations</th>
                    <th>Max Population</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentSessions as $session): ?>
                <tr>
                    <td><?php echo $session['id']; ?></td>
                    <td><?php echo $session['username']; ?></td>
                    <td><?php echo $session['start_time']; ?></td>
                    <td><?php echo $session['end_time'] ?? 'In Progress'; ?></td>
                    <td><?php echo $session['generations']; ?></td>
                    <td><?php echo $session['max_population']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>