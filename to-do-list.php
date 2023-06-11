<?php

session_start();

$mysqli = require __DIR__ . "/database.php";

$userid = $_SESSION["user_id"];

    if(isset($_POST['submit'])){

        $task = $_POST['task'];
     
        $sql = "INSERT INTO tasks (task, userid) VALUES ('$task','$userid')";
     
        $stmt = $mysqli -> stmt_init();
        
        if (! $stmt->prepare($sql)){
         die("Error: " . $mysqli->error);
         }
     
         if ($stmt->execute()){
             header("Location: to-do-list.php");
             exit;
         } 
}

/*

$mysqli = require __DIR__ . "/database.php";

if(isset($_POST['submit'])){

    $task = $_POST['task'];

   $sql = "INSERT INTO tasks (task) VALUES ('$task')";

   $stmt = $mysqli -> stmt_init();
   
   if (! $stmt->prepare($sql)){
    die("Error: " . $mysqli->error);
    }

    if ($stmt->execute()){
        header("Location: to-do-list.php");
        exit;
    } 

}*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
</head>
<body>
    <header>
        <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </nav>
    </header>

    
    <main>
        <h1>To-Do List</h1>

        <form action="to-do-list.php" method="post">
            <input type="text" name="task" id="taskentry" placeholder="Type task here.">
            <button type="submit" name="submit">Add Task</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>Placeholder</td>
                    <td>
                        <a href="#">x</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

</body>
</html>