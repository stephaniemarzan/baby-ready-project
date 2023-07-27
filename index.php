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

    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.typekit.net/ufc4fds.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/460eab7b7d.js" crossorigin="anonymous"></script>


</head>
<body>

    <header>

         <nav>
            <ul>
                <li><a href="index.php" class="nav-logo">Baby Ready</a></li>
                <li class="nav-item-push"><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>

        <?php if (isset($user)): ?>

         <img src="images/outfit.jpg" alt="Image of baby outfit" class="header-image">

        <?php endif; ?>
        
    </header>

    <main class="container">

        <a href="index.php"><img src="images/main-logo.svg" alt="Baby Ready Logo" class="main-logo"></a>

        <?php if (isset($user)): ?>
        
        <div class="main-content">
            <h2><?= htmlspecialchars($user["name"])?>, let's get baby ready!</h2>

            <div class="home-nav">         
                
                <div class="home-nav-options">
                    <a href="to-do-list.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <style>svg{fill:#c67fb2}</style>
                        <path d="M0 96C0 60.7 28.7 32 64 32H512c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM128 288a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm32-128a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM128 384a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm96-248c-13.3 0-24 10.7-24 24s10.7 24 24 24H448c13.3 0 24-10.7 24-24s-10.7-24-24-24H224zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H448c13.3 0 24-10.7 24-24s-10.7-24-24-24H224zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H448c13.3 0 24-10.7 24-24s-10.7-24-24-24H224z"/>
                        </svg>
                    </a>
                    <h3><a href="to-do-list.php"> To-Do List</a></h3>
                </div>

                <div class="home-nav-options">
                    <a href="shopping-list.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <style>svg{fill:#c67fb2}</style>
                        <path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160V112zm-48 48H48c-26.5 0-48 21.5-48 48V416c0 53 43 96 96 96H352c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48H336V112C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>
                        </svg>
                    </a>
                    <h3><a href="shopping-list.php"> Shopping List</a></h3>
                </div>
            </div>   
        </div>


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