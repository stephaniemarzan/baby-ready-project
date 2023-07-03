<?php

session_start();

$mysqli = require __DIR__ . "/database.php";

$userid = $_SESSION["user_id"];

$error = "";

// Add task to database

$task = $_POST['task'];

    if(isset($_POST['submit'])){

        if(empty($task)){
            $error = "Please type your task to add it.";
        }else{
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
     
}

// Task Deleted

if(isset($_GET['delete_task'])){
    $taskdeleteid = $_GET['delete_task'];
    mysqli_query($mysqli,"DELETE FROM tasks WHERE id=$taskdeleteid");
    header('location: to-do-list.php');
}

// Display Task Variables

    $tasks = mysqli_query($mysqli, "SELECT * FROM tasks WHERE userid = '$userid'");
    $tasknumber = 1;

    $edit_state = false;

// Task Edits

if(isset($_GET['edit_task'])){
    $id = $_GET['edit_task'];
    $edit_state = true;
    $rec = mysqli_query($mysqli, "SELECT * FROM tasks WHERE id = '$id'");
    $record = mysqli_fetch_array($rec);
    $task = $record['task'];
    $id = $record['id'];
}

if(isset($_POST['update'])){
    $task = $_POST['task'];
    $id = $_POST['id'];

    mysqli_query($mysqli, "UPDATE tasks SET task='$task' WHERE id=$id");
    header('location: to-do-list.php');

}

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
             <li><a href="index.php">Home</a></li>
             <li><a href="shopping-list.php">Shopping List</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </nav>
    </header>

    
    <main>
        <h1>To-Do List</h1>

        <form action="to-do-list.php" method="post">
        
        <?php if(isset($error)){ ?>

            <p><?php echo $error ?></p>

            <?php } ?>

            <input type="hidden" name="id" value="<?php echo $id;?>">

            <input type="text" name="task" id="taskentry" placeholder="Type task here." value="<?php echo $task; ?>">

            <?php if($edit_state == false):?>
                <button type="submit" name="submit">Add Task</button>
            <?php else: ?>
                <button type="submit" name="update">Update</button>
            <?php endif ?>

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

            <?php 
            while ($row = mysqli_fetch_array($tasks)){ ?>
                <tr>
                    <td><?php echo $tasknumber++; ?></td>
                    <td><?php echo $row['task']; ?></td>
                    <td>
                        <a href="to-do-list.php?delete_task=<?php echo $row['id'];?>">x</a>
                    </td>
                    <td>
                        <a href="to-do-list.php?edit_task=<?php echo $row['id'];?>">Edit</a>
                    </td>
                </tr>
            <?php
            } ?>
            
            </tbody>
        </table>
    </main>

</body>
</html>