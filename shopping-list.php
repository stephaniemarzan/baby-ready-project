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

    </main>

</body>
</html>