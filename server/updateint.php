<?php session_start(); ?>
<?php
  //session_start();
  $json = $_POST['data'];
  //$json = '{"tags":["国内","财经","娱乐","国际","体育","教育"]}';
  //$tags = json_decode($json)->tags;
  $tags = json_decode($json,TRUE, JSON_UNESCAPED_UNICODE);
  //echo var_dump($tags);
  $tags = $tags["tags"];
  $servername = "localhost";
  $username = "bs";
  $password = "123456";
  $dbname = "web_news";
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  if(isset($_SESSION['email'])) {
    $email=$_SESSION['email'];
    //$email = "jyj1996@163.com";
    $conn->exec("DELETE FROM `subscribe` WHERE email=\"$email\"");
    $res = $conn->prepare('SELECT tid FROM tags WHERE tname=?');
    $seq = array();
    foreach($tags as $key=>$value) {
      $res->execute(array($value));
      $seq[$key] = $res->fetch()['tid'];
    }
    $res = $conn->prepare("INSERT INTO subscribe VALUES (\"$email\", ?, ?)");
    foreach($seq as $key=>$value) {
      $res->execute(array($value, $key));
    }
    echo json_encode('true');
  }
  else {
    echo json_encode('false');
  }
?>
