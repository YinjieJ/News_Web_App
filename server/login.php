<?php
  // ini_set('session.cookie_domain', '.');
  // $sessSavePath = dirname(__FILE__).'/session/';
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>正在跳转</title>
</head>
<body>
<?php
    $servername = "localhost";
    $username = "bs";
    $password = "123456";
    $dbname = "web_news";

    try {
        // echo "Connected successfully";
        //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $email = $_POST['email'];
        $passwd = $_POST['password'];
        $stmt = "SELECT * FROM users where email = \"$email\" and passwd = \"$passwd\"";
        //echo $stmt;
        $cnt = $conn->query($stmt);
        if($cnt != false && $cnt->rowCount() > 0){
          $_SESSION['email'] = $email;
          $email = $_SESSION['email'];
          //echo $email;
          echo "<meta http-equiv=\"refresh\" content=\"0;URL=../index/index.html\">";
        }
        else {
          //session_destroy();
          echo "<p align=center> <font color=\"#0066ff\" size=\"2\">邮箱或密码错误,请重新操作...</font>";
          echo "<span style=\"font-size:18px;\"> </span><span style=\"font-size:24px;\"><meta http-equiv=\"refresh\" content=\"3;URL=../index/login.html\"> </span>
  <span style=\"font-size:24px;\"></p>";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    $conn = null;
?>
  <!-- <p align="center"> 如果您的浏览器不支持跳转,<a style="text-decoration: none" href="#"><font color="#FF0000">请点这里</font></a>返回首页.</p> -->
</body>
</html>
