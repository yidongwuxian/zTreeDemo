<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>用车申请</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--标准mui.css-->
    <link rel="stylesheet" href="/static/mui/css/mui.min.css">
    <!--时间控件css-->
    <link rel="stylesheet" href="/static/mui/css/mui.picker.min.css">
    <!--App自定义的css-->
    <link rel="stylesheet" type="text/css" href="/static/mui/css/app.css" />
    <script src="/static/mui/js/mui.min.js"></script>
    <style>
        h5 {
            margin: 5px 7px;
        }
    </style>
</head>

<body>
<style>
    #blockbtn button{
        width: 49%;
        float: left;
    }
</style>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">用车申请</h1>
</header>
    <div class="mui-content">
        <div class="mui-content-padded" style="margin: 5px;">
            <form class="mui-input-group">
                <div class="mui-input-row">
                    <label>用车日期</label>
                        <input type="text" id="chooseDateTxt" value="<?php echo date('Y-m-d', time()) ;?>">
                </div>
                <div class="mui-input-row">
                    <label>用车时段</label>
                        <input type="text" id="chooseRangeTxt" placeholder="用车时段">
                </div>
                <div class="mui-input-row">
                    <label>用车人数</label>
                    <div class="mui-numbox">
                        <button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
                        <input class="mui-input-numbox" type="number" name="person_num" id="person_num" value="1"/>
                        <button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
                    </div>
                </div>
                <div class="mui-input-row">
                    <label>用车数</label>
                    <div class="mui-numbox">
                        <button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
                        <input class="mui-input-numbox" type="number" name="cars_num" id="cars_num"  value="1"/>
                        <button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
                    </div>
                </div>
                <div class="mui-input-row">
                    <label>联系方式</label>
                    <input type="text" class="mui-input-clear" value="" name="mobile" id="mobile">
                </div>
                <div class="mui-input-row">
                    <label>出发地</label>
                    <input type="text" class="mui-input-clear" value="" name="start_city" id="start_city">
                </div>
                <div class="mui-input-row">
                    <label>目的地</label>
                    <input type="text" class="mui-input-clear" value="" name="end_city" id="end_city">
                </div>
                <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                    <textarea name="reason" id="reason" rows="5" placeholder="请填写事由..."></textarea>
                </div>
                <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                    <textarea name="desc" id="desc" rows="5" placeholder="其他说明..."></textarea>
                </div>
            </form>
            <div class="mui-button-row" id="blockbtn">
                <button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="btn" style="margin-right:6px;">确定</button>
                <button type="button" class="mui-btn mui-btn-danger mui-btn-block">取消</button>
            </div>

        </div>
    </div>
</body>
</html>
<script src="/static/mui/js/zepto.min.js"></script>
<script src="/static/mui/js/mui.picker.min.js"></script>
<script src="/static/mui/js/mui.date.min.js"></script>
<script>
    mui.init();
    var btn = document.getElementById("btn");

    btn.addEventListener('tap',function(){
        //var apply_id = document.getElementById("apply_id").value;
        var use_date = document.getElementById("chooseDateTxt").value;
        var use_time = document.getElementById("chooseRangeTxt").value;
        var person_num = document.getElementById("person_num").value;
        var cars_num = document.getElementById("cars_num").value;
        var mobile = document.getElementById("mobile").value;
        var start_city = document.getElementById("start_city").value;
        var end_city = document.getElementById("end_city").value;
        var reason = document.getElementById("reason").value;
        var desc = document.getElementById("desc").value;

        if ( use_time=='' ){
            mui.alert('请选择用车时段');
            return false;
        }
        if ( person_num < 1 ){
            mui.alert('请选择人数');
            return false;
        }
        if ( cars_num < 1 ){
            mui.alert('请输入用车数');
            return false;
        }
        if ( mobile=='' ){
            mui.alert('请输入联系方式');
            return false;
        }
        var patten = /^1[3,5,8,7]\d{9}$/;
        var isMobile = patten.test(mobile);
        if ( !isMobile ){
            mui.alert('手机号格式错误');
            return false;
        }
        if ( start_city=='' ){
            mui.alert('请输入出发地');
            return false;
        }
        if ( end_city=='' ){
            mui.alert('请输入目的地');
            return false;
        }
        if ( reason=='' ){
            mui.alert('请输入申请事由');
            return false;
        }
        mui.post('/app/car/applycar',{
//            id:apply_id,
            use_date:use_date,
            use_time:use_time,
            person_num:person_num,
            cars_num:cars_num,
            mobile:mobile,
            start_city:start_city,
            end_city:end_city,
            reason:reason,
            desc:desc
        },function(json){
            if( json.result == 1 ){
                mui.alert('申请成功', '提示', function()
                {
                    window.location='/app/car/apply';
                });
            }else{
                mui.alert( json.message );
            }
        },'json');
    });

</script>
