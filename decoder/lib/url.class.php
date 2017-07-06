<?php
include_once 'simple_html_dom.php';
class UrlJx {
    public $url;//url地址
    public $html;//获取url的html文档
    public $zh_html;//html的传入编码
    public $urlcode;//文件编码
    public $time;//时间
    
    //初始构造方法
    public function __construct($url) {
             $this->url=$url;
             $this->html=file_get_html($this->url);
             $this->urlcode=$this->GetCode();
             $this->zh_html=$this->SetCode();
             $this->time=$this->gettime();
          }

          //获取编码方法
        public function GetCode(){
              $charsets=$this->html->find('meta[charset]');
              $charset1=null;
              $pattern="/(?<=charset=).*/is";
              if ($charsets){
                  $charset=$charsets[0]->charset;
                  return  $charset;
              }else{
                  $charsets=$this->html->find('meta[content]');
                  for ($i=0;$i<sizeof($charsets);$i++){
                      $charset1=$charsets[$i]->content;
                      preg_match_all($pattern,$charset1,$charset,PREG_SET_ORDER);
                      if ($charset){
                          foreach($charset as $k=>$val){
                              if( is_array($val)){
                                  foreach( $val as $value){
                                      return $value;
                                      break;
                                  }
                              }else{
                                 
                                  return $val;
                                  break;
                              }
                          }
                      }
                  }
                  return "none";
              }
          }
   //转换编码方法
   public  function SetCode()
          {
              if (!($this->urlcode=='utf-8'))
              {
                //  return $this->html;
                //  return iconv(trim($this->urlcode), "UTF-8", $this->html);
                 
                  $pattern="/(?<=chinanews|cri|yangtse|news365|cnr|qq).*/is";//排除特别的网站编码
                  preg_match_all($pattern,$this->url,$charset,PREG_SET_ORDER);
                  if (!($charset))
                  {  
                    
                      return  mb_convert_encoding($this->html,"UTF-8",$this->urlcode);
                  } else 
                  {
                      
                      return $this->html;
                  }
              } else
              {
                  return $this->html;
              }
          }
          
   //转换编码方法
   public  function SetCode1($str)
          {
              if (!($this->urlcode=='utf-8'))
              {
                  return iconv($this->urlcode, "UTF-8", $str);
              } else
              {
                  return $str;
              }
          }
          
          //获取时间
  public   function gettime()
          {
              $string = file_get_contents($this->url);
              $matches=null;
              if (preg_match_all('/\d{4}-\d{1,2}-\d{1,2} \d{2}:\d{2}/', $string, $matches) > 0) {
                  return  $this->SetCode1($matches[0][0]);
              } else{
                  if (preg_match_all('/\d{4}年\d{1,2}月\d{1,2}日 \d{2}:\d{2}/', $string, $matches) > 0) {
                      return  $this->SetCode1($matches[0][0]);
                  } else{
                      if (preg_match_all('/\d{4}年\d{1,2}月\d{1,2}日\d{2}:\d{2}/', $string, $matches) > 0) {
                          return  $this->SetCode1($matches[0][0]);
                      }
                      else
                          if (preg_match_all('/\d{4}年\d{1,2}月\d{1,2}日', $string, $matches) > 0) {
                              return  $this->SetCode1($matches[0][0]);
                          }
                          else
                              if (preg_match_all('/\d{4}-\d{1,2}-\d{1,2}', $string, $matches) > 0) {
                                  return  $this->SetCode1($matches[0][0]);
                              }
                  }
              }
          }

          
          
}
