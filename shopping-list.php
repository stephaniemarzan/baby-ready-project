<?php
session_start();

$mysqli = require __DIR__ . "/database.php";

$userid = $_SESSION["user_id"];

$error = "";

// Add item to database

$item = $_POST['item'];
$shortened_link = $_POST['item-link'];

    if(isset($_POST['submit-item'])){

        if(empty($item)){
            $error = "Links are optional. However, you must enter an item.";
        }else{
            $sql = "INSERT INTO shopping (item,link,userid) VALUES ('$item','$shortened_link', '$userid')";
     
            $stmt = $mysqli -> stmt_init();
            
            if (! $stmt->prepare($sql)){
             die("Error: " . $mysqli->error);
             }
         
             if ($stmt->execute()){
                 header("Location: shopping-list.php");
                 exit;
             } 
        }

}

// Display Item Variables

$items = mysqli_query($mysqli, "SELECT * FROM shopping WHERE userid = '$userid' AND done is NULL");
$doneitems = mysqli_query($mysqli, "SELECT * FROM shopping WHERE userid = '$userid' AND done='1'");
$itemnumber = 1;

$edit_state = false;

// Delete Item

if(isset($_GET['delete_item'])){
    $itemdeleteid = $_GET['delete_item'];
    mysqli_query($mysqli,"DELETE FROM shopping WHERE id=$itemdeleteid");
    header('location: shopping-list.php');
}

// Item + Link Edits

if(isset($_GET['edit_item'])){
    $id = $_GET['edit_item'];
    $edit_state = true;
    $rec = mysqli_query($mysqli, "SELECT * FROM shopping WHERE id = '$id'");
    $record = mysqli_fetch_array($rec);
    $item = $record['item'];
    $shortened_link = $record['link'];
    $id = $record['id'];
}

if(isset($_POST['update'])){
    $item = $_POST['item'];
    $shortened_link = $_POST['item-link'];
    $id = $_POST['id'];

    mysqli_query($mysqli, "UPDATE shopping SET item='$item', link='$shortened_link' WHERE id=$id");
    header('location: shopping-list.php');

}

// Done Item

if(isset($_GET['done_item'])){
    $itemdoneid = $_GET['done_item'];
    mysqli_query($mysqli,"UPDATE shopping SET done='1'  WHERE id=$itemdoneid");
    header('location: shopping-list.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An application for expectant parents. This application will allow you to organize your tasks and create a shopping list in order to get you ready for your baby."> 
    <title>Shopping List</title>

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
            <li><a href="index.php" class="home-link">Home</a></li>
             <li><a href="to-do-list.php">To Do List</a></li>
             <li class="nav-item-push"><a href="logout.php">Logout</a></li>
        </ul>
        </nav>
    </header>
    
    <main class="container">

        <img src="images/submark.svg" alt="Baby Ready Logo Submark" class="main-logo">

        <div class="main-content">

            <h1>Shopping List</h1>

                <form action="shopping-list.php" method="post">

                    <div class="input-entry">
                    <input type="text" name="link-entry" id="link-entry" placeholder="Link entry here.">
                    <button type="submit" class="btn" name="slink-submit">Shorten Link</button>
                    </div>
                </form>


            <?php

            $api_url = "https://api-ssl.bitly.com/v4/bitlinks";
            $token = "5e4e23c45796f9c9464a228731c4fdea5eadee1a";
            $url = $_POST ['link-entry'];
        
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["long_url" => $url]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
            ]);
    
            $link_result = json_decode(curl_exec($ch));

            $slink_submit= $_POST['slink-submit'];

             if(isset($slink_submit)){ ?>
            <p class="shortened-link">Your shortened URL is: <?php echo $link_result->link;  ?></p> 
            <?php }?>


                <form action="shopping-list.php" method="post">

                <?php if(isset($error)){ ?>

                    <p><?php echo $error ?></p>

                    <?php } ?>


                    <div class="input-entry">
                        <input type="hidden" name="id" value="<?php echo $id;?>">

                        <input type="text" name="item" id="itementry" placeholder="Your item name here." value="<?php echo $item; ?>">

                        <input type="text" name="item-link" id="itemlink" placeholder="The short link here." value="<?php echo $shortened_link; ?>">
                    
                        <?php if($edit_state == false):?>
                        <button type="submit" name="submit-item" class="btn">Add Item</button>
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
                            <th>Item</th>
                            <th>Link</th>
                            <th>Edit</th>
                            <th>Done</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($items)){ ?>
                            <tr>
                                <td data-cell="#" class="list-control"><?php echo $itemnumber++; ?></td>
                                <td data-cell="Item" class="list-control"><?php echo $row['item']; ?></td>
                                <td data-cell="Link" class="list-control"><?php echo $row['link']; ?></td>
                                <td data-cell="Edit" class="list-control">
                                    <a href="shopping-list.php?edit_item=<?php echo $row['id'];?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td data-cell="Done" class="list-control">
                                    <a href="shopping-list.php?done_item=<?php echo $row['id'];?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td data-cell="Delete" class="list-control">
                                    <a href="shopping-list.php?delete_item=<?php echo $row['id'];?>">
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
            </div>

            <hr>

            <div class="completed-items">
                <h2>Items Bought</h2>
                <div class="list-area-complete">
                    <table class="list-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                        <tbody>
                        <?php
                            while ($row = mysqli_fetch_array($doneitems)){ ?>
                                <tr>
                                    <td data-cell="Item"><?php echo $row['item']; ?></td>
                                    <td data-cell="Link"><?php echo $row['link']; ?></td>
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