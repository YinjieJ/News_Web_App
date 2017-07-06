<?php session_start(); ?>
<?php
  //header('Content-Type:text/html;charset=utf-8');
  $servername = "localhost";
  $username = "bs";
  $password = "123456";
  $dbname = "web_news";
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  if(isset($_SESSION['email'])) {
    $email=$_SESSION['email'];
    //$email = "jyj1996@163.com";
    $psw = $_POST['password'];
    $npsw = $_POST['cpassword'];
    // $gender = 0; $job = "在校学生"; $age = 22;
    $res = $conn->query("SELECT * FROM users WHERE email = \"$email\" AND passwd = \"$psw\"");
    if($res != false && $res->rowCount() > 0) {
      if($npsw != "")$conn->exec("UPDATE users SET passwd=\"$npsw\" WHERE email=\"$email\"");
      $url = "../index/index.html";
      echo "<html><body>";
      echo "<script language=\"javascript\" type=\"text/javascript\">";
      echo 'alert("设置成功");';
      echo "window.location.href=\"$url\"";
      echo "</script></html></body>";
    } else {
      $url = "../index/setting.html";
      echo "<html><body>";
      echo "<script language=\"javascript\" type=\"text/javascript\">";
      echo 'alert("密码错误");';
      echo "window.location.href=\"$url\"";
      echo "</script></html></body>";
    }
  }
  else {
    $url = "../index/login.html";
    echo "<script language='javascript' type='text/javascript'>";
    echo 'alert("请先登录");';
    echo "window.location.href=\"$url\"";
    echo "</script>";
  }
  $conn= NULL;
?>
