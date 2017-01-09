<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>个人信息</title>
    <meta name="apple-mobile-web-app-cable" content="yes"/><!-- 删除苹果默认的工具栏和菜单栏-->
    <meta name="apple-mobile-web-app-status-bar-style" content="#574C87"/><!--设置苹果的工具栏颜色 -->
    <meta name="viewport" content="width=device-width,initial-scale-1,user-scalable=no"/>
    <link rel="stylesheet" href="/Public/static/edp/css/myStyle.css">
</head>
<body>
<section class="message-container">
    <form id="form">
    <section class="message-contant">
    <div class="message-item">
        <div class="message-item-name">姓 名</div>
        <input type='text' id="real_name" name="real_name">
    </div>
    <div class="message-item">
        <div class="message-item-name">身份证号</div>
        <input type='number' id="card" name="id_number">
    </div>
    <div class="message-item">
        <div class="message-item-name">性 别</div>
        <select class="message-item-contant" name="gender" id="gender">
            <option value="男">男</option>
            <option value="女">女</option>
        </select>
    </div>
    <div class="message-item">
        <div class="message-item-name">籍 贯</div>
        <input type='text' name="province">
    </div>
    <div class="message-item">
        <div class="message-item-name">QQ 号</div>
        <input type='text' id="qq" name="qq">
    </div>
    <div class="message-item">
        <div class="message-item-name">邮 箱</div>
        <input type='email' id="email" name="email">
    </div>
</section>



    <section class="message-contant">
        <div class="message-item">
            <div class="message-item-name">单 位</div>
            <input type='text' name="company">
        </div>
        <div class="message-item">
            <div class="message-item-name">职 务</div>
            <input type='text' name="company_title">
        </div>
        <div class="message-item">
            <div class="message-item-name">单位地址</div>
            <input type='text' name="company_addr">
        </div>
        <div class="message-item">
            <div class="message-item-name">办公电话</div>
            <input type='number' id="company_phone" name="company_phone">
        </div>
    </section>



    <section class="message-contant">
        <div class="message-item">
            <div class="message-item-name">学 历</div>
            <input type='text' name="degree">
        </div>
        <div class="message-item">
            <div class="message-item-name">毕业院校</div>
            <input type='text' name="school">
        </div>
        <div class="message-item">
            <div class="message-item-name">毕业时间</div>
            <input type='month' id="date" value="" name="finish_school">
        </div>
    </section>
    </form>

    <input type="button" value="完成" class="message-done" id="done">
</section>
<script type="text/javascript" src="/Public/static/edp/js/jquery-3.1.1.min.js"></script>
<script>
    var isIDCard1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;
    var isIDCard2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
    var isQQ=/^\d[1-9]{5,10}$/;
    var isEmail=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var test01=0,test02=0,test03=0,test04=0;
    var inputLength=$(".message-item input").length-1;
    $("#done").click(function () {
            $(".message-item input").each(function (i,n) {
                if(!$(n).val()){
                    alert($(n).prev().text()+' 不能为空');
                    test01=0;
                    return false;
                }else {

                    if(i == inputLength){
                        test01=1;
                        past();
                    }
                }

            });
            function past() {
                if(isIDCard1.test($("#card").val()) || isIDCard2.test($("#card").val())){
                    test02=1;
                }else {
                    alert("请填写正确的身份证号");
                    test02=0;
                    return false;
                }
                if(!isQQ.test($("#qq").val())){
                    alert('请输入正确的qq号');
                    test03=0;
                    return false;
                }else {
                    test03=1;
                }
                if(!isEmail.test($('#email').val())){
                    alert('请输入正确的邮箱');
                    test04=0;
                    return false;
                }else {
                    test04=1;
                }

            }
        if(test04 && test03 &&test02 &&test01){
            $.ajax({
                type: "POST",
                url: "<?php echo U('Edp/editprofile');?>",
                dataType: 'json',
                async: false,
                data: $("#form").serialize(),
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

    })
</script>
</body>
</html>