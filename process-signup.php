<?php

if (empty($_POST["name"])) {
    die("Please enter a name.");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Please enter a valid email address");
}

if ($_POST["password"] !== $_POST["password-confirmation"]){
    die("The passwords entered do not match.");
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
    die("An account with this email address may already exist.");
    }
}


?>