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

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.typekit.net/ufc4fds.css">

</head>
<body>

    <header>
    </header>

    <main class="container">

        <a href="index.php"><img src="images/main-logo.svg" alt="Baby Ready Logo" class="main-logo"></a>

        <?php if (isset($user)): ?>

        <h2><?= htmlspecialchars($user["name"])?>, let's get baby ready!</h2>

        <h3><a href="to-do-list.php"> To-Do List</a></h3>
        <h3><a href="shopping-list.php"> Shopping List</a></h3>


        <?php else: ?>
        
        <div class="main-content">
                
            <a href="login.php"><button class="btn">Login</button></a>
            <a href="signup.html"><button class="btn">Signup</button></a>

        </div>

        <?php endif; ?>

    </main>

        <footer>
            <p>Website by Stephanie Marzan</p>
        </footer>
    
</body>
</html>