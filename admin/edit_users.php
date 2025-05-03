<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = (int)$_GET['id'];

// Get user data
$stmt = $conn->prepare("SELECT id, username, is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: users.php");
    exit();
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    // Check if username exists (except for current user)
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error_message = "Username already exists. Please choose another.";
    } else {
        // If password is provided, update it too
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, is_admin = ? WHERE id = ?");
            $stmt->bind_param("ssii", $username, $hashed_password, $is_admin, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, is_admin = ? WHERE id = ?");
            $stmt->bind_param("sii", $username, $is_admin, $user_id);
        }
        
        if ($stmt->execute()) {
            $success_message = "User updated successfully.";
            
            // Refresh user data
            $stmt = $conn->prepare("SELECT id, username, is_admin FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Error updating user: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
    <title>Edit User</title>
    <style>
        .admin-container {
            max-width: 800px;
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
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .checkbox-label {
            display: inline-block;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-nav">
            <h1>Edit User</h1>
            <div>
                <a href="users.php"><button>Back to Users</button></a>
            </div>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password">
            </div>
            
            <div class="form-group">
                <input type="checkbox" id="is_admin" name="is_admin" <?php echo $user['is_admin'] ? 'checked' : ''; ?>>
                <label for="is_admin" class="checkbox-label">Admin Privileges</label>
            </div>
            
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>