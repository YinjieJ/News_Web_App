function GetRequest() {
  var url = location.search; //获取url中"?"符后的字串
  var str = "";
  if (url.indexOf("?") != -1) {
    str = url.substr(1);
  }
  return str;
}

function loadNews(){
  var email;
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
  $.ajax({
    url: "../decoder/decode.php",
    data: {nid:str},
    success: function(json){
      $('#news').append("<h2>" + json.title + "</h2>");
      $('#news').append("<span style = \"margion:%3\"><small>来源： " + json.author + " </samll></span>");
      $('#news').append("<span><small> 时间：" + json.time + "</samll></span>");
      $('#news').append(json.content);
      $('img').not("[src ^= http]").attr("src", function(n, v){	// 选择相对地址的资源
        console.log(json);	
        pos = json.url.lastIndexOf('/');	// 从源链接获取目录路径
        return json.url.substring(0, pos)+ '/' + v;		// 修正资源地址
      })
    },
    dataType: "JSON",
    error: function(xhr,status,error) {
      alert("错误提示： " + xhr.status + " " + xhr.statusText + " " + error);
    }
  });
  $.getJSON("../server/getinfo.php",{kind:"recom", tag:""}, function(json){
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

function logout() {
  $.getJSON("../server/getinfo.php",{kind:"logout"});
}

function jump(id) {
  window.location.href = "news.html?"+id;
}
