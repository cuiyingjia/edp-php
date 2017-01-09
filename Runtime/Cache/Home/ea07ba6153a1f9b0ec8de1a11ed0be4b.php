<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
    <meta name="apple-mobile-web-app-cable" content="yes"/><!-- 删除苹果默认的工具栏和菜单栏-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><!--设置苹果的工具栏颜色 -->
    <meta name="viewport" content="width=device-width,initial-scale-1,user-scalable=no"/>
    <link rel="stylesheet" href="/Public/static/edp/css/myStyle.css">
</head>
<body>
<!--<header class="register-header">-->
<!--<div>注册</div>-->
<!--</header>-->
<section class="register-container">
    <section class="register-contant">
        <div class="register-item">
            <input type="mobile" id="phone" placeholder="  手机号">
        </div>
        <div class="register-item register-code">
            <input type="text" placeholder="  验证码" id="auth">
            <div class="register-item-code" onclick='sendAuthCode();' id="send">
                获取验证码
            </div>
        </div>
        <div class="register-item">
            <input type="password" placeholder="  密码" id="pwd">
        </div>
    </section>
    <input type="button" value="完成注册" class="personal-next">
</section>
<script type="text/javascript" src="/Public/static/edp/js/jquery-3.1.1.min.js"></script>
<script>
    var isPhone=/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/;
    var isPwd=/^[0-9A-Za-z]{6,18}$/;
    var isCode=/^\d{4}$/;
    var test01=0;
    var test02=0;
    var inputLength = $('.register-item input').length-1;
    $('.personal-next').click(function () {
        $('.register-item input').each(function (i,n) {
            if(!$(n).val()){
                alert($(n).attr('placeholder')+'不能为空');
                test01=0;
                return false;
            }else {
                if(i == inputLength){
                    test01=1;
                    past();
                }
            }
        });
    });
    function past() {
        if(isPhone.test($("#phone").val()) && isPwd.test($('#pwd').val()) && isCode.test($('#auth').val())){
            test02=1;
        }else {
            test02=0;
            if(!isPhone.test($("#phone").val())){
                alert('手机号码格式错误');
                return false;
            }
            if(!isCode.test($("#auth").val())){
                alert('验证码格式有误');
                return false;
            }
            if(!isPwd.test($("#pwd").val())){
                alert('密码格式错误，密码应以字母开头，长度在6-18之间，只能包含字母和数字');
                return false;
            }
        }
        if(test01 && test02){
            register();
        }
    }
    function register() {
        $.ajax({
            type: "POST",
            url: "<?php echo U('Edp/register');?>",
            dataType: 'json',
            async: false,
            data: {
                mobile: $("#phone").val(),
                password:$("#pwd").val(),
                authcode:$("#auth").val()
            },
            success: function(data){
                if(data['status'] == 1){
                    window.location.href="<?php echo U('Edp/login');?>";
                }
                if(data['status'] == 0){
                    alert(data['info']);
                }
            },
            error:function (data) {
                alert('ajax错误!');
            }
        });
    }

    function sendAuthCode(){
        var phone = $("#phone").val();
        if(!isPhone.test(phone)){
            alert('请输入正确的手机号');
            return;
        }
        $("#send").attr("onclick",null);
        $("#send").attr("disabled","disabled");
        send();
        $.ajax({
            type: "POST",
            url: "<?php echo U('Edp/sendMsg');?>",
            dataType: 'json',
            async: false,
            data: {
                mobile: phone
            },
            success: function(data){
               if(data['status'] == 1){
                   alert('短信已发送，请注意查收!');
               }else{
                   alert(data['info']);
               }
            },
            error:function (data) {
                alert('ajax错误!');
            }
        });

    }

    var sec = 61;
    function send() {
        sec--;
        if (sec <= 0) {
            document.getElementById("send").innerHTML="获取验证码";
            $("#send").attr("onclick","sendAuthCode();");
            sec = 61;
            return null;
        } else if (sec>0){
            document.getElementById("send").innerHTML = "重新发送" + sec ;
            setTimeout("send();", 1000);
        }
    }



</script>
</body>
</html>