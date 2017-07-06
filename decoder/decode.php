<?php session_start(); ?>
<?php
$id = $_GET['nid'];
require 'lib/Readability.inc.php';
require 'lib/simple_html_dom.php';
require 'lib/url.class.php';

$servername = "localhost";
$username = "bs";
$password = "123456";
$dbname = "web_news";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$email = $_SESSION['email'];
$url = 'http://education.news.cn/2016-05/15/c_1118868373.htm';
$conn->exec("INSERT INTO `click`(`email`, `nid`) VALUES (\"$email\", \"$id\");");
foreach ($conn->query("SELECT author, anchor FROM news WHERE nid=\"$id\"") as $row) {
  $url = $row['anchor'];
  $author = $row['author'];
}
//echo $url;
$res = decode($url);
$res['author'] = $author;
$res['url'] = $url;
//print_r($res);
function decode($url){
    $html=new UrlJx($url);
    //print_r($html);
    $Readability= new Readability($html->zh_html,"utf-8"); // default charset is utf-8
    $Data = $Readability->getContent();
    $Data['time']=$html->time;
    return $Data;
}
$json_obj = json_encode($res, JSON_UNESCAPED_UNICODE);
echo $json_obj;
?>
