function setInfo() {
  $('#submit').attr("type", "submit");
  $('#submit').removeAttr('onclick');
  $('#table').attr("action", "../server/updateinfo.php");
  $('#modify').html("\
  <div class=\"form-group\">\
    <label class=\"control-label col-sm-2\" for=\"gender\">性别:</label>\
    <div class=\"col-sm-4\">\
        <label class=\"radio-inline\"><input type=\"radio\" name=\"gender\" value=\"男\" checked>男</label>\
        <label class=\"radio-inline\"><input type=\"radio\" name=\"gender\" value=\"女\">女</label>\
    </div>\
  </div>\
  <div class=\"form-group\">\
    <label class=\"control-label col-sm-2\" for=\"age\">年龄:</label>\
    <div class=\"col-sm-4\">\
      <input type=\"age\" class=\"form-control\" name=\"age\" id=\"age\" placeholder=\"请输入您的年龄\">\
    </div>\
  </div>\
  <div class=\"form-group\">\
    <label class=\"control-label col-sm-2\" for=\"job\">职业:</label>\
    <div class=\"col-sm-4\">\
      <select class=\"form-control\" name=\"job\" id=\"job\">\
        <option>在校学生</option>\
        <option>企/事业员工</option>\
        <option>服务业人员</option>\
        <option>农林牧业相关</option>\
        <option>待业</option>\
        <option>其他</option>\
      </select>\
    </div>\
  </div>\
  ");
}

function setPasswd() {
  $('#submit').removeAttr('onclick');
  $('#submit').attr("type", "submit");
  $('#table').attr("action", "../server/updatepsw.php");
  $('#table').attr("method", "Post");
  $('#modify').html("\
  <div class=\"form-group\">\
    <label class=\"control-label col-sm-2\" for=\"password\">旧密码:</label>\
    <div class=\"col-sm-4\">\
      <input type=\"password\" class=\"form-control\" name=\"password\" id=\"password\" placeholder=\"请输入您的密码\">\
    </div>\
  </div>\
  <div class=\"form-group\">\
    <label class=\"control-label col-sm-2\" for=\"cpassword\">新密码:</label>\
    <div class=\"col-sm-4\">\
      <input type=\"password\" class=\"form-control\" name=\"cpassword\" id=\"cpassword\" placeholder=\"请确认您的密码\">\
    </div>\
  </div>\
  ")
}

var prior = new Array();
var num = 0;

function show(){
  $('#result').html("<h4>选择结果：</h4><ol class=\"list-group\"></ol>");
  for(var i=0; i < prior.length; i++) {
    $('#result>ol').append(
      "<li class=\"list-group-item\">"+ prior[i].tag +"</li>");
  }
}
function setPriority(str) {			// 复选框的点击事件
  if($('#'+str).is(':checked')) {		// 选中
    for(var i=0; i < prior.length; i++) {	// 选中标签优先级更新
      if(prior[i].tag == str) prior[i].p = num;
    }
    num ++;	// 选中标签数增加
  } else {		// 取消选择
    num--;		// 选中标签数减少
    var temp;
    for(var i=0; i < prior.length; i++) {
      if(prior[i].tag == str) {	// 取消选择的标签优先级重置
        temp = prior[i].p;
        prior[i].p = 100;
      }
      if(prior[i].p > temp && prior[i].p != 100) prior[i].p--;	// 更新选中标签优先级
    }
  }
  prior.sort(function(a,b){	// 排序
      return a.p-b.p});
  show();
}

function sub(){
  $.ajax({
    type:"Post",
    url: "../server/updateint.php",
    dataType: "JSON",
    data:{
    data: function(){
      var array = new Array();
      for(var i = 0; i < prior.length; i++) {
        array[i] = prior[i].tag;
      }
      //console.log($.fn.stringify(array));
      var jsObj = {};
      jsObj.tags = array;
      var str = JSON.stringify(jsObj);
      console.log(str);
      return str;
    }},
    success: function(result){
      console.log(result);
      if(result=="false") alert("设置失败，请重试！");
      else {
        alert("设置成功");
        location.href ="index.html";
      }
    },
    error: function(xhr,status,error) {
      alert("错误提示： " + xhr.status + " " + xhr.statusText + " " + error);
      //alert(result);
    }
  })
}

function setInterst() {
  //$('#table').attr("action", "../server/updateinte.php");
  $("#modify").attr("class", "form-group");
  $("#modify").html("<div class=\"container-fluid text-center\"></div>");
  var node = $("#modify>div:first");
  node.append("<div class=\"col-sm-offset-2 col-sm-4 text-left\" style=\"padding-bottom:2%\"><h4>请按序选择兴趣模块：</h4></div>");
  node.append("<div class=\"col-sm-4 text-left\" id = \"result\"><h4>选择结果：</h4></div>");
  var child1 = $("#modify>div:first>div:first");
  var child2 = $("#modify>div:first>div:last");
  var tags;
  $('#submit').attr("type", "button");
  $('#submit').attr("onclick", "sub()");
  $.getJSON("../server/getinfo.php",{kind:"tags"}, function(json){
    var times = 0;
    $.each(json.tags, function(i, item) {
        child1.append(
          "<div class=\"checkbox\">"+
          "<label><input style=\"zoom:1.3\" type=\"checkbox\" value=\""+ item +"\" id=\""+ item +
          "\" onclick=\"setPriority(\'"+ item +"\')"+
          "\">"+"<span style=\"zoom:1.3\">"+
          item + "</span></label>"+
          "</div>"
        );
        function Tag(p, tag) {
          this.p = p;
          this.tag = tag;
        }
        var t = new Tag(100, item);
        prior[times]=t;
        times++;
    })
    show();
  })
}
