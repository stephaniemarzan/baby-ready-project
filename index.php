<?php

session_start();

if(isset($_SESSION["user_id"])){

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli ->query($sql);

    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baby Ready</title>
</head>
<body>

    <header>

    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
        
    </header>

        <h1>Baby Ready</h1>

        <?php if (isset($user)): ?>

            <h2>Welcome, <?= htmlspecialchars($user["name"])?>!</h2>

        <?php else: ?>

        <button><a href="login.php">Login</a></button>
        <button><a href="signup.php">Signup</a></button>

        <?php endif; ?>

    
</body>
</html>