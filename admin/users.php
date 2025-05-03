<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  
  // Don't allow deleting yourself
  if ($id !== $_SESSION['user_id']) {
      $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
      $stmt->bind_param("i", $id);
      
      if ($stmt->execute()) {
          $success_message = "User deleted successfully.";
      } else {
          $error_message = "Error deleting user: " . $conn->error;
      }
  } else {
      $error_message = "You cannot delete your own account.";
  }
}

// Handle admin toggle
if (isset($_GET['admin'])) {
  $id = (int)$_GET['admin'];
  $admin_status = (int)$_GET['status'];
  $new_status = $admin_status ? 0 : 1;
  
  $stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
  $stmt->bind_param("ii", $new_status, $id);
  
  if ($stmt->execute()) {
      $success_message = "User admin status updated.";
  } else {
      $error_message = "Error updating user: " . $conn->error;
  }
}

// Get all users
$sql = "SELECT id, username, created_at, is_admin FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      $users[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles.css">
  <title>User Management</title>
  <style>
      .admin-container {
          max-width: 1000px;
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
      .action-buttons a {
          margin-right: 10px;
          text-decoration: none;
      }
  </style>
</head>
<body>
  <div class="admin-container">
      <div class="admin-nav">
          <h1>User Management</h1>
          <div>
              <a href="dashboard.php"><button>Dashboard</button></a>
              <a href="../logout.php"><button>Log Out</button></a>
          </div>
      </div>
      
      <?php if (isset($success_message)): ?>
          <div class="message success"><?php echo $success_message; ?></div>
      <?php endif; ?>
      
      <?php if (isset($error_message)): ?>
          <div class="message error"><?php echo $error_message; ?></div>
      <?php endif; ?>
      
      <table>
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Created At</th>
                  <th>Admin</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($users as $user): ?>
                  <tr>
                      <td><?php echo $user['id']; ?></td>
                      <td><?php echo $user['username']; ?></td>
                      <td><?php echo $user['created_at']; ?></td>
                      <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                      <td class="action-buttons">
                          <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                          <a href="users.php?admin=<?php echo $user['id']; ?>&status=<?php echo $user['is_admin']; ?>"><?php echo $user['is_admin'] ? 'Remove Admin' : 'Make Admin'; ?></a>
                          <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                              <a href="users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                          <?php endif; ?>
                      </td>
                  </tr>
              <?php endforeach; ?>
              
              <?php if (empty($users)): ?>
                  <tr>
                      <td colspan="5">No users found.</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>
</body>
</html>