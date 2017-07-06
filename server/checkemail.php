<?php
    $servername = "localhost";
    $username = "bs";
    $password = "123456";
    $dbname = "web_news";

    try {
        // echo "Connected successfully";
        //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $email = $_POST['email'];
        // $email = "jyj1996@163.com";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $res = $conn->query("SELECT * FROM users where email=\"$email\"");
        if ($res->rowCount() > 0) echo "false";
        else echo "true";
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    $conn = null;
    // echo "false";
?>
