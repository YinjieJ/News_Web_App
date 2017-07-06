<?php session_start() ?>
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
        $gender = $_POST['gender'] == '男'? 0: 1;
        // echo $_POST['gender'];
        $uname = $_POST['name']; $email = $_POST['email']; $password =$_POST['password']; $job = $_POST['job']; $age = $_POST['age'];
        $stmt = "INSERT INTO users (uname, email, passwd, job, gender, age) VALUES (\"$uname\", \"$email\", \"$password\", \"$job\", $gender, $age)";
        $cnt = $conn->exec($stmt);
        if($cnt > 0) {
          $sql = "SELECT tid FROM tags ORDER BY tid";
          $res = $conn->prepare("INSERT INTO subscribe VALUES (\"$email\", ?, ?)");
          $i = 0;
          foreach ($conn->query($sql) as $row) {
            $res->execute(array($row['tid'], $i));
            $i++;
          }
          $_SESSION['email'] = $email;
          echo 'true';
        }
        else {
          session_destroy();
          echo "false";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    $conn = null;
?>
  <p align="center"> 如果您的浏览器不支持跳转,<a style="text-decoration: none" href="../index/index.html"><font color="#FF0000">请点这里</font></a>.</p>
</body>
</html>
