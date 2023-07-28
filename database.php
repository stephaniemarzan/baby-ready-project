<?php

/*for testing
$host = 'localhost';
$dbname = "babyproject";
$username = "root";
$password = "root";*/

//for web server
$host = '208.109.71.15';
$dbname = "babyproject";
$username = "smarzan";
$password = "password1";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno){
    die("Connection error:" . $mysqli->connect_error);
}

return $mysqli;

