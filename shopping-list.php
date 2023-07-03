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

$items = mysqli_query($mysqli, "SELECT * FROM shopping WHERE userid = '$userid'");
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
</head>
<body>
    <header>
        <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
             <li><a href="to-do-list.php">To Do List</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </nav>
    </header>
    
    <main>
        <h1>Shopping List</h1>
        
        <form action="shopping-list.php" method="post">
            <input type="text" name="link-entry" id="link-entry" placeholder="Link entry here.">
            <button type="submit">Shorten Link</button>
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

        ?>

        <p>Your shortened URL is: <?php echo $link_result->link;  ?></p> 


        <form action="shopping-list.php" method="post">

        <?php if(isset($error)){ ?>

            <p><?php echo $error ?></p>

            <?php } ?>

            <input type="hidden" name="id" value="<?php echo $id;?>">

            <input type="text" name="item" id="itementry" placeholder="Enter the item name here." value="<?php echo $item; ?>">

            <input type="text" name="item-link" id="itemlink" placeholder="Enter the shortened link here." value="<?php echo $shortened_link; ?>">
           
            <?php if($edit_state == false):?>
             <button type="submit" name="submit-item">Add Item</button>
             <?php else: ?>
                <button type="submit" name="update">Update</button>
            <?php endif ?>

        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php 
                while ($row = mysqli_fetch_array($items)){ ?>
                    <tr>
                        <td><?php echo $itemnumber++; ?></td>
                        <td><?php echo $row['item']; ?></td>
                        <td><?php echo $row['link']; ?></td>
                        <td>
                            <a href="shopping-list.php?delete_item=<?php echo $row['id'];?>">x</a>
                        </td>
                        <td>
                            <a href="shopping-list.php?edit_item=<?php echo $row['id'];?>">Edit</a>
                        </td>
                    </tr>
                <?php
                } ?>
            
            </tbody>
        
        </table>


    </main>

</body>
</html>