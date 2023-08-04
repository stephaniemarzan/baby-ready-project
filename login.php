<?php

$pass_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if($user){
       if (password_verify($_POST["password"], $user["password_hash"])){

        session_start();

        session_regenerate_id();

        $_SESSION["user_id"] = $user["id"];

        header("Location: index.php");
        exit;
       }
    }

    $pass_invalid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An application for expectant parents. This application will allow you to organize your tasks and create a shopping list in order to get you ready for your baby."> 
    <title>Home Page</title>
    

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.typekit.net/ufc4fds.css">

</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php" class="nav-logo">Baby Ready</a></li>
                <li class="nav-item-push"><a href="signup.html">Sign Up</a></li>
            </ul>
        </nav>

        <img src="images/baby-feet.jpg" alt="Image of baby feet" class="header-image">

    </header>

        <main class="container">

        <a href="index.php"><img src="images/main-logo.svg" alt="Baby Ready Logo" class="main-logo"></a>

            <div class="form-user">
                <form method="post">
                    <div class="form-input">
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "")?>" required placeholder="Email Address">
                    </div>

                    <div class="form-input">
                        <input type="password" name="password" id="password" required placeholder="Password">
                    </div>

                    <button type="submit" class="btn">Log In</button>

                    <?php if($pass_invalid): ?>
                        <p>The password entered is incorrect. Please try again.</p>
                    <?php endif; ?>
                    
                </form>

            </div>

        </main>

    <footer>
            <p>Website by Stephanie Marzan</p>
    </footer>
    
</body>
</html>