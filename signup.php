<?php
    $message = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    
        $file = 'users.txt';
        $userExists = false;
    
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
            foreach ($lines as $line) {
                list($existingUser, $existingPass) = explode(',', $line);
                if ($existingUser === $username) {
                    $userExists = true;
                    break;
                }
            }
        }
    
        if ($userExists) {
            $message = "Username already exists. Please choose another.";
        } else {
            $newUser = $username . ',' . $password . "\n";
            file_put_contents($file, $newUser, FILE_APPEND);
            $message = "Sign up successful! <a href='login.php'>Go to login</a>";
        }
    }    



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="main">
        <h1>Sign Up</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Sign Up</button>
        </form>
    </div>


</body>
</html>

<?php
