<?php session_start(); ?>
<?php
  $servername = "localhost";
  $username = "bs";
  $password = "123456";
  $dbname = "web_news";
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $kind = $_GET['kind'];
  if(isset($_SESSION['email'])) $email=$_SESSION['email'];
  else $email = '';
  //$kind = 'recom'; $email = '';
  switch ($kind) {
    case 'email':
      //$json_arr = array("email"=>$res);
      $json_arr = array("email"=>$email);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    case 'name':
      $name = '';
      if($email != '') {
        $sql = "SELECT uname FROM users where email=\"$email\"";
        foreach($conn->query($sql) as $row) {
          $name = $row['uname'];
        }
      }
      $json_arr = array("name"=>$name);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    case 'tags':
      $sql = "SELECT tname FROM tags ORDER BY tid";
      foreach($conn->query($sql) as $row) {
        $tags[] = $row['tname'];
      }
      $json_arr = array("tags"=>$tags);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    case 'inter':
      $sql = "SELECT tname FROM tags NATURAL JOIN subscribe where email=\"$email\" ORDER BY priority";
      foreach($conn->query($sql) as $row) {
        $tags[] = $row['tname'];
      }
      $json_arr = array("tags"=>$tags);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    case 'logout':
      session_destroy();
      break;
    case 'news':
      $tag = $_GET['tag'];
      // $tag = "娱乐";
      $sql = "SELECT * FROM news NATURAL JOIN (news_tag NATURAL JOIN tags) WHERE tname = \"$tag\" ORDER BY `pub_time` DESC";
      foreach($conn->query($sql) as $row) {
        $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
      }
      $json_arr = array("news"=>$news);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    case 'recom':
      $tag = $_GET['tag'];
      $news = array();
      if($email == '') {
          $sql = "SELECT * FROM news WHERE nid NOT IN (SELECT nid FROM news_tag NATURAL JOIN tags WHERE tname = \"$tag\") ORDER BY `pub_time` DESC";
          $num = 0;
          foreach($conn->query($sql) as $row) {
            $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
            $num++;
            if($num > 20) break;
          }
          $sql = "SELECT * FROM news WHERE nid NOT IN (SELECT nid FROM click) ORDER BY `pub_time`";
          $num = 0;
          foreach($conn->query($sql) as $row) {
            $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
            $num++;
            if($num > 10) break;
          }
      } else {
        $sql = "SELECT * FROM news NATURAL JOIN click WHERE email IN\n"
          . "(SELECT email FROM users A WHERE A.email != \"$email\" AND EXISTS \n"
          . "(SELECT 1 FROM users B WHERE B.email = \"$email\" AND A.job = B.job))\n"
          . "OR email IN \n"
          . "(SELECT email FROM users A WHERE A.email != \"$email\" AND EXISTS \n"
          . "(SELECT 1 FROM users B WHERE B.email = \"$email\" AND A.age - B.age BETWEEN -5 AND 5 AND A.gender = B.gender))"
          . "ORDER BY pub_time DESC";
          $num = 0;
          foreach($conn->query($sql) as $row) {
            $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
            $num++;
            if($num > 20) break;
          }
          if($num < 20) {
            $sql = "SELECT * FROM news WHERE nid NOT IN (SELECT nid FROM news_tag NATURAL JOIN tags WHERE tname = \"$tag\") ORDER BY `pub_time` DESC";
            foreach($conn->query($sql) as $row) {
              $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
              $num++;
              if($num > 20) break;
            }
          }
          $sql = "SELECT * FROM news WHERE nid NOT IN (SELECT nid FROM click) ORDER BY `pub_time`";
          $num = 0;
          foreach($conn->query($sql) as $row) {
            $news[] = array('id' => $row['nid'], 'title' => $row['title'], 'author' => $row['author'], 'link' => $row['anchor'], 'description' => $row['content']);
            $num++;
            if($num > 10) break;
          }
      }
      $json_arr = array("news"=>$news);
      $json_obj = json_encode($json_arr, JSON_UNESCAPED_UNICODE);
      echo $json_obj;
      break;
    default:
      # code...
      break;
  }

?>
