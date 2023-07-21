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
    <title>Home Page</title>

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.typekit.net/ufc4fds.css">

</head>
<body>
    <nav>
         <ul>
            <li><a href="signup.html">Sign Up</a></li>
        </ul>
    </nav>

        <main class="container">

        <img src="images/main-logo.svg" alt="Baby Ready Logo" class="main-logo">

        <form method="post">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "")?>" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Log In</button>
        </form>

        <?php if($pass_invalid): ?>
            <p>The password entered is incorrect. Please try again.</p>
        <?php endif; ?>

        </main>

    <footer>
            <p>Website by Stephanie Marzan</p>
    </footer>
    
</body>
</html>