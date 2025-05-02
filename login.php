<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $file = 'users.txt';
        $userExists = false;

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                list($existingUser, $existingPass) = explode(',', $line);
                if ($existingUser === $username && $existingPass === $password) {
                    $userExists = true;
                    break;
                }
            }
        }

        if ($userExists) {
            $_SESSION['username'] = $username;
            header("Location: game.php");
            exit();
        } else {
            $message = "Invalid username or password.";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Log In</title>
</head>
<body>
    <div class="main">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Log In</button>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>