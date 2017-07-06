<?php
function prepareDB($sql) {
  //INSERT INTO `news`(`nid`, `title`, `author`, `anchor`, `pub_time`, `content`, `image`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
  $servername = "localhost";
  $username = "bs";
  $password = "123456";
  $dbname = "web_news";
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $env = $conn->prepare($sql);
  // print_r($env);
  return $env;
}

function insertDB($item, $env1, $env2) {
  static $nid = 5000;
  if(!isset($item['author']) || $item['author'] == null) $item['author'] = "WWW.CHINANEWS.COM";
  if($item['author'] == "SINA.com") {
    $url = $item['link'];
    $pos = stripos($url, "http");
    $pos = strripos(substr($url, $pos+1), "http");
    if($pos == false) return;
    else $url = substr($url, $pos);
  }
  // echo $nid." ";
  $cnt = $env1->execute(array((string)$nid, $item['title'], $item['author'], $item['link'], $item['description'], $item['link']));
  if($cnt != false && $cnt > 0) {
    $env2->execute(array((string)$nid));
    $nid++;
  } // else print_r($item);
}

function readRss($rssfeed, $env1, $env2) {
  $buff = "";
  //打开rss地址，并读取
  $fp = fopen($rssfeed,"r") or die("can not open $rssfeed");
  while ( !feof($fp) ) {
  $buff .= fgets($fp,4096);
  }
  //关闭文件
  fclose($fp);
  //建立一个 XML 解析器
  $parser = xml_parser_create();
  //xml_parser_set_option -- 为指定 XML 解析进行选项设置
  xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
  //xml_parse_into_struct -- 将 XML 数据解析到数组$values中
  xml_parse_into_struct($parser,$buff,$values,$idx);
  //xml_parser_free -- 释放指定的 XML 解析器
  xml_parser_free($parser);
  //print_r($values);
  $isRead = false; $isComplete = false;
  $item = array();
  foreach ($values as $val) {
    $tag = $val["tag"];
    $tag = strtolower($tag);
    $type = $val["type"];
    // echo $tag." ".$type."\n";
    if($tag == "item" && $type == "open") {
      $isRead = true;
      continue;
    } else if($tag == "item" && $type == "close") {
      $isRead = false;
      $isComplete = true;
    }
    if($isRead == true) {
      if(isset($val["value"])) {
        $value = $val["value"];
        $item[$tag] = $value;
      } else {
        $item[$tag] = null;
      }
    }
    if($isComplete == true) {
      // print_r($item);
      insertDB($item, $env1, $env2);
      $isComplete = false;
    }
  }
}
function addNewsTag($rssfeed, $tid) {
  $sql = "INSERT INTO `news`(`nid`, `title`, `author`, `anchor`, `content`) SELECT ?, ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS(SELECT nid FROM news WHERE anchor = ?);";
  $env1 = prepareDB($sql);
  $sql = "INSERT INTO news_tag VALUES (?, \"$tid\")";
  $env2 = prepareDB($sql);
  readRss($rssfeed, $env1, $env2);
}
header('Content-Type:text/html;charset= UTF-8');
while(1) {
  $rssfeed ='http://www.xinhuanet.com/politics/news_politics.xml';
  addNewsTag($rssfeed, "1");
  $rssfeed ='http://rss.sina.com.cn/news/china/focus15.xml';
  addNewsTag($rssfeed, "1");
  $rssfeed ='http://news.qq.com/newsgn/rss_newsgn.xml';
  addNewsTag($rssfeed, "1");
  $rssfeed = 'http://rss.sina.com.cn/news/world/focus15.xml';
  addNewsTag($rssfeed, "2");
  $rssfeed = 'http://www.xinhuanet.com/world/news_world.xml';
  addNewsTag($rssfeed, "2");
  $rssfeed = 'http://finance.qq.com/financenews/breaknews/rss_finance.xml';
  addNewsTag($rssfeed, "3");
  $rssfeed = 'http://rss.sina.com.cn/news/allnews/finance.xml';
  addNewsTag($rssfeed, "3");
  $rssfeed = 'http://www.xinhuanet.com/fortune/news_fortune.xml';
  addNewsTag($rssfeed, "3");
  $rssfeed =  'http://rss.sina.com.cn/ent/hot_roll.xml';
  addNewsTag($rssfeed, "4");
  $rssfeed = 'http://www.chinanews.com/rss/ent.xml';
  addNewsTag($rssfeed, "4");
  $rssfeed = 'http://www.xinhuanet.com/ent/news_ent.xml';
  addNewsTag($rssfeed, "4");
  $rssfeed = 'http://www.chinanews.com/rss/sports.xml';
  addNewsTag($rssfeed, "5");
  $rssfeed = 'http://www.xinhuanet.com/sports/news_sports.xml';
  addNewsTag($rssfeed, "5");
  $rssfeed = 'http://rss.sina.com.cn/edu/focus19.xml';
  addNewsTag($rssfeed, "6");
  $rssfeed = 'http://www.xinhuanet.com/edu/news_edu.xml';
  addNewsTag($rssfeed, "6");
  $sql = "DELETE FROM `news` WHERE datediff(now(), pub_time) > 2";
  $servername = "localhost";
  $username = "bs";
  $password = "123456";
  $dbname = "web_news";
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->exec($sql);
  $conn = null;
  sleep(600);
  // break;
}
?>
