function setIndex() {
  var email;
  function GetRequest() {
    var url = location.search; //获取url中"?"符后的字串
    var str = "";
    if (url.indexOf("?") != -1) {
      str = url.substr(1);
    }
    return str;
  }
  var str = GetRequest();
  $.getJSON("../server/getinfo.php",{kind:"email"}, function(json){
    email = JSON.stringify(json.email);
    console.log((email));
    if(email.indexOf("@") > -1)
    { $('#myNavbar>ul:last').html(
      "<li><a href=\"setting.html\"><span class = \"glyphicon glyphicon-wrench\"/>设置</a></li>"+
      "<li onclick=\"logout()\"><a href=\"index.html\"><span class = \"	glyphicon glyphicon-log-out\"/>退出</li>"
    );}
    getTags();
    function getTags() {
      function addTags(json) {
        $('#myNavbar>ul:first').html("");
        $.each(json.tags, function(i, item) {
          $('#myNavbar>ul:first').append(
            "<li><a href=\"index.html?"+ i +"\">"+ item +"</a></li>"
          );
          if(i==0 && str == "") getNews(item);
          else if (str == i) getNews(item);
        });
      }
      if(email.indexOf("@") > -1)
        $.getJSON("../server/getinfo.php",{kind:"inter"}, function(json){
          addTags(json);
        });
      else
        $.getJSON("../server/getinfo.php",{kind:"tags"}, function(json){
          addTags(json);
        });
    }
  });
}

function logout() {
  $.getJSON("../server/getinfo.php",{kind:"logout"});
}

function getNews(tag) {
  $('#myNavbar>ul:first>li:contains('+ tag +')').attr("class", "active");
  $('#news').html("<h1>欢迎访问聚合新闻！</h1>");
  $.getJSON("../server/getinfo.php",{kind:"news", tag:tag}, function(json){
    $.each(json.news, function(i, item) {
      str = item.description == null? "": item.description;
      str = str.replace(/<.*htm>/, "");
      str = str.replace(/<.a>/, "");
      //console.log(str);
      $('#news').append(
        "<div class=\"panel panel-default\">\
        	<div class=\"panel-heading\" onclick=\"jump("+item.id+")\">\
            <h3 class=\"panel-title\">" +
            item.title +
            "</h3>\
        	</div>\
        	<div class=\"panel-body\"><a href=# onclick=\"jump("+item.id+")\"><p>"+
            str + "</p></a>" +
            "<small>source:" + item.author + "</small>" +
        	"</div>\
        </div>"
      )
    });
  });
  $.getJSON("../server/getinfo.php",{kind:"recom", tag:tag}, function(json){
    $('#recom').html("<h2>新闻推荐</h2>");
    $.each(json.news, function(i, item) {
      str = item.description == null? "": item.description;
      str = str.replace(/<.*htm>/, "");
      str = str.replace(/<.a>/, "");
      $('#recom').append(
        "<div class=\"panel panel-default\">\
        	<div class=\"panel-heading\" onclick=\"jump("+item.id+")\">\
            <h3 class=\"panel-title\">" +
            item.title +
            "</h3>\
        	</div>\
        	<div class=\"panel-body\"><a href=# onclick=\"jump("+item.id+")\"><p>"+
            str + "</p></a>" +
            "<small>source:" + item.author + "</small>" +
        	"</div>\
        </div>"
      )
    });
  });
}

function jump(id) {
  window.location.href = "news.html?"+id;
}
