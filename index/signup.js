$(document).ready(function() {// 在键盘按下并释放及提交后验证提交表单
 var validate =  $("#form").validate({
   submitHandler:function(form){},
    rules: {
      name: {
        required: true,
        minlength: 2
      },
      password: {
        required: true,
        minlength: 6
      },
      cpassword: {
        required: true,
        minlength: 6,
        equalTo: "#password"
      },
      email: {
        required: true,
        email: true,
        remote: {
            url: "../server/checkemail.php",     //后台处理程序
            type: "post",               //数据发送方式
            // dataType: "json",           //接受数据格式
            data: {                     //要传递的数据
                email: function() {
                    // console.log($("#email").val());
                    return $("#email").val();
                }
            }}
      },
      gender: "required",
      age: {
          required: true,
          range: [1,130]
      }
    },
    messages: {
      name: {
        required: "请输入用户名",
        minlength: "用户名至少为两个字符"
      },
      password: {
        required: "请输入密码",
        minlength: "密码长度不能小于五个字符"
      },
      cpassword: {
        required: "请输入密码",
        minlength: "密码长度不能小于五个字符",
        equalTo: "两次密码输入不一致"
      },
      email:{
          required: "请输入邮箱",
          remote: "该邮箱已被注册",
          email: "邮箱不正确"
      },
      age: {
          required: "请输入您的年龄",
          range: "请输入合理的年龄"
      }
  }})
  })

  var checkSubmitFlg = true;
  function signup() {
    var flag = $("#form").valid();
    if(flag && checkSubmitFlg) {
      checkSubmitFlg = false;
      $.ajax({
        cache: false,
        type: "POST",
        url:"../server/signup.php",
        data:$('#form').serialize(),
        async: true,
        success: function(result){
          console.log(result);
          if(result=="false") {
            alert("注册失败，请重试！");
            checkSubmitFlg = true;
          }
          else {
            alert("注册成功");
            location.href ="index.html";
          }
        },
      });
    }
  }
