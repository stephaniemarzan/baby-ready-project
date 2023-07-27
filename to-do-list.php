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

    $tasks = mysqli_query($mysqli, "SELECT * FROM tasks WHERE userid = '$userid' AND done is NULL");
    $donetasks = mysqli_query($mysqli, "SELECT * FROM tasks WHERE userid = '$userid' AND done='1'");
    $tasknumber = 1;
    $completedtasknumber = 1;

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

// Task Done

if(isset($_GET['done_task'])){
    $taskdoneid = $_GET['done_task'];
    mysqli_query($mysqli,"UPDATE tasks SET done='1'  WHERE id=$taskdoneid");
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
             <li><a href="index.php">Home</a></li>
             <li><a href="shopping-list.php">Shopping List</a></li>
            <li class="nav-item-push"><a href="logout.php">Logout</a></li>
        </ul>
        </nav>
    </header>

    
    <main class="container">

        <img src="images/submark.svg" alt="Baby Ready Logo Submark" class="main-logo">

        <div class="main-content">
            <h1>To-Do List</h1>

                <form action="to-do-list.php" method="post">

                <?php if(isset($error)){ ?>

                    <p><?php echo $error ?></p>

                    <?php } ?>
                    <div class="input-entry">

                        <input type="hidden" name="id" value="<?php echo $id;?>">

                        <input type="text" name="task" id="taskentry" placeholder="Type task here." value="<?php echo $task; ?>">

                        <?php if($edit_state == false):?>
                            <button type="submit" name="submit" class="btn">Add Task</button>
                        <?php else: ?>
                            <button type="submit" name="update" class="btn">Update</button>
                        <?php endif ?>

                    </div>

                </form>
                
                <div class="list-area">
                    <table class="list-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Edit</th>
                                <th>Done</th>
                                <th>Delete</th>

                            </tr>
                        </thead>

                        <tbody>

                        <?php 
                        while ($row = mysqli_fetch_array($tasks)){ ?>
                            <tr>
                                <td data-cell="#"><?php echo $tasknumber++; ?></td>
                                <td data-cell="Task"><?php echo $row['task']; ?></td>

                                <td data-cell="Edit">
                                    <a href="to-do-list.php?edit_task=<?php echo $row['id'];?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td data-cell="Done">
                                    <a href="to-do-list.php?done_task=<?php echo $row['id'];?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td data-cell="Delete">
                                    <a href="to-do-list.php?delete_task=<?php echo $row['id'];?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        } ?>
                        
                        </tbody>
                    </table>

                    <div class="completed-items">
                        <h2>Completed Tasks</h2>

                            <table>
                                <tbody>
                                <?php 
                                    while ($row = mysqli_fetch_array($donetasks)){ ?>
                                        <tr>
                                            <td data-cell="#"><?php echo $completedtasknumber++; ?></td>
                                            <td><?php echo $row['task']; ?></td>
                                        </tr>
                                    <?php
                                    } ?>
                                </tbody>
                            </table>

                    </div>
    

                </div>
                
        </div>

    </main>

    <footer>
            <p>Website by Stephanie Marzan</p>
    </footer>

</body>
</html>