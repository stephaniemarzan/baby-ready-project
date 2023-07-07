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
    </header>

    <main>

        <h1>Baby Ready</h1>

        <?php if (isset($user)): ?>

        <h2><?= htmlspecialchars($user["name"])?>, let's get baby ready!</h2>

        <h3><a href="to-do-list.php"> To-Do List</a></h3>
        <h3><a href="shopping-list.php"> Shopping List</a></h3>


        <?php else: ?>

        <button><a href="login.php">Login</a></button>
        <button><a href="signup.html">Signup</a></button>

        <?php endif; ?>

    </main>

        <footer>
            <p>Website by Stephanie Marzan</p>
        </footer>
    
</body>
</html>