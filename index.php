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
        header("Location: homepage.html");
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
</head>
<body>
    <h1?>Baby Ready</h1>

    <form method="post">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "")?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <button type="submit">Log In</button>
    </form>

    <?php if($pass_invalid): ?>
        <p>The password entered is incorrect. Please try again.</p>
    <?php endif; ?>
    
</body>
</html>