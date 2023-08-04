<?php

$errorsignup = false;

?><!DOCTYPE html>
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
    </header>

    <main class="container">

    <h1>It looks like we found some issues..</h1>

    <?php

        if (empty($_POST["name"])) {
            $errorsignup = true;
            ?>
            <p class="error-signup">Please enter a name.</p>
        <?php
        }
        if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errorsignup = true;
            ?>
            <p class="error-signup">Please enter a valid email address.</p>
        <?php
        }

        if(strlen($_POST["password"])<8){
            $errorsignup = true;
            ?>
            <p class="error-signup">Please enter a password that is at least 8 characters.</p>
        <?php
        }

        if(! preg_match("/[a-z]/i", $_POST["password"])){
            $errorsignup = true;
            ?>
            <p class="error-signup">Your password must contain at least one letter.</p>
            <?php
        }

        if(! preg_match("/[0-9]/i", $_POST["password"])){
            $errorsignup = true;
            ?>
            <p class="error-signup">Your password must contain at least one number.</p>
            <?php
        }

        if ($_POST["password"] !== $_POST["password-confirmation"]){
            $errorsignup = true;
            ?>
            <p class="error-signup">The passwords entered do not match.</p>
            <?php
        }

        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $mysqli = require __DIR__ . "/database.php";

        $sql = "INSERT INTO user (name, email, password_hash)
                VALUES (?, ?, ?)";

        $stmt = $mysqli -> stmt_init();

        if (! $stmt->prepare($sql)){
            die("Error: " . $mysqli->error);
        }

        $stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

        if ($stmt->execute()){
            header("Location: login.php");
            exit;
        } else {
            if ($mysqli->errno === 1062){
                $errorsignup = true;
                ?>
                <p class="error-signup">An account with this email address may already exist. Use a different email address or try logging in, instead.</p>
                <a href='login.php'><button class="btn">Login</button></a>
                <?php
            }
        }

        if($errorsignup === true){
            ?>
                <a href="signup.html"><button class="btn">Try Again</button></a>
            <?php
        }
    ?>

    </main>

</hmtl>