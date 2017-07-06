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
    $gender = $_POST['gender'] == '男'? 0: 1;
    $job = $_POST['job']; $age = $_POST['age'];
    // $gender = 0; $job = "在校学生"; $age = 22;
    if(age != "")$conn->exec("UPDATE users SET age=$age where email=\"$email\"");
    $conn->exec("UPDATE users SET gender=$gender where email=\"$email\"");
    $conn->exec("UPDATE users SET job=\"$job\" where email=\"$email\"");
    $url = "../index/index.html";
    echo "<html><body>";
    echo "<script language=\"javascript\" type=\"text/javascript\">";
    echo 'alert("设置成功");';
    echo "window.location.href=\"$url\"";
    echo "</script></html></body>";
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
