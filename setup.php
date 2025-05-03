<?php
// Display setup page if not submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Game of Life - Setup</title>
    <style>
        .setup-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>Conway's Game of Life - Database Setup</h1>
        <p>This script will create the necessary database and tables for the Game of Life project.</p>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="db_host">Database Host:</label>
                <input type="text" id="db_host" name="db_host" value="localhost" required>
            </div>
            
            <div class="form-group">
                <label for="db_user">Database Username:</label>
                <input type="text" id="db_user" name="db_user" value="root" required>
            </div>
            
            <div class="form-group">
                <label for="db_pass">Database Password:</label>
                <input type="password" id="db_pass" name="db_pass" value="">
            </div>
            
            <div class="form-group">
                <label for="db_name">Database Name:</label>
                <input type="text" id="db_name" name="db_name" value="game_of_life" required>
            </div>
            
            <div class="form-group">
                <label for="admin_user">Admin Username:</label>
                <input type="text" id="admin_user" name="admin_user" value="admin" required>
            </div>
            
            <div class="form-group">
                <label for="admin_pass">Admin Password:</label>
                <input type="password" id="admin_pass" name="admin_pass" value="admin123" required>
            </div>
            
            <button type="submit">Run Setup</button>
        </form>
    </div>
</body>
</html>
<?php
exit();
}

// Process the setup
$db_host = $_POST['db_host'];
$db_user = $_POST['db_user'];
$db_pass = $_POST['db_pass'];
$db_name = $_POST['db_name'];
$admin_user = $_POST['admin_user'];
$admin_pass = $_POST['admin_pass'];

// Connect to MySQL
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = [];

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    $messages[] = "Database '$db_name' created successfully.";
} else {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($db_name);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    $messages[] = "Table 'users' created successfully.";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create game_sessions table
$sql = "CREATE TABLE IF NOT EXISTS game_sessions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP NULL,
    generations INT(11) DEFAULT 0,
    max_population INT(11) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    $messages[] = "Table 'game_sessions' created successfully.";
} else {
    die("Error creating game_sessions table: " . $conn->error);
}

// Create admin user
$hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $admin_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 1)");
    $stmt->bind_param("ss", $admin_user, $hashed_password);
    
    if ($stmt->execute()) {
        $messages[] = "Admin user '$admin_user' created successfully.";
    } else {
        $messages[] = "Error creating admin user: " . $stmt->error;
    }
} else {
    $messages[] = "Admin user already exists.";
}

// Create config.php file
$config_content = "<?php
// Database configuration
\$db_host = '$db_host';
\$db_user = '$db_user';
\$db_pass = '$db_pass';
\$db_name = '$db_name';

// Create connection
\$conn = new mysqli(\$db_host, \$db_user, \$db_pass, \$db_name);

// Check connection
if (\$conn->connect_error) {
    die('Connection failed: ' . \$conn->connect_error);
}
?>";

if (file_put_contents('config.php', $config_content)) {
    $messages[] = "Configuration file 'config.php' created successfully.";
} else {
    $messages[] = "Error creating configuration file. Please create it manually with the following content:";
    $messages[] = "<pre>$config_content</pre>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Game of Life - Setup Complete</title>
    <style>
        .setup-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>Setup Complete</h1>
        
        <?php foreach ($messages as $message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endforeach; ?>
        
        <p>The setup has been completed successfully. You can now use the Game of Life application.</p>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="index.html"><button>Go to Home Page</button></a>
        </div>
    </div>
</body>
</html>