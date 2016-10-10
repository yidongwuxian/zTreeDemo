<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>申请详情</title>
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
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">申请详情</h1>
</header>
    <div class="mui-content">
        <div class="mui-content-padded" style="margin: 5px;">
            <form class="mui-input-group">
                <div class="mui-input-row">
                    <label>用车日期</label>
                        <input readonly="true" type="text" value="<?php echo date('Y-m-d', $data->use_date) ;?>">
                </div>
                <div class="mui-input-row">
                    <?php if($data->status != 3 && $mark != 3):?>
                    <input type="hidden" id="chooseDateTxt">
                    <?php endif;?>
                    <input type="hidden" id="chooseRangeTxt"">
                    <label>用车时段</label>
                    <input readonly="true" type="text" value="<?php echo $data->use_time ;?>">
                </div>
                <div class="mui-input-row">
                    <label>用车人数</label>
                        <input readonly="true" type="text" name="person_num" id="person_num" value="<?php echo $data->person_num ;?> 人"/>
                </div>
                <div class="mui-input-row">
                    <label>用车数</label>
                        <input readonly="true" type="text" value="<?php echo $data->cars_num ;?> 辆"/>
                </div>
                <div class="mui-input-row">
                    <label>申请人</label>
                    <input readonly="true" type="text" value="<?php echo $data->member_name ;?>"/>
                </div>
                <div class="mui-input-row">
                    <label>联系方式</label>
                    <input readonly="true" type="text" value="<?php echo $data->mobile ;?>" name="mobile" id="mobile">
                </div>
                <div class="mui-input-row">
                    <label>出发地</label>
                    <input readonly="true" type="text" value="<?php echo $data->start_city ;?>" name="start_city" id="start_city">
                </div>
                <div class="mui-input-row">
                    <label>目的地</label>
                    <input readonly="true" type="text" value="<?php echo $data->end_city ;?>" name="end_city" id="end_city">
                </div>
                <div class="mui-input-row" style="margin: 0px 5px;height: 89px;">
                    <h5 style="margin: 11px;">事由</h5>
                    <textarea readonly="true" name="reason" id="reason" rows="5" placeholder="请填写事由..."><?php echo $data->reason ;?></textarea>
                </div>
                <div class="mui-input-row" style="margin: 0px 5px;height: 89px;">
                    <h5 style="margin: 11px;">其他说明</h5>
                    <textarea readonly="true" name="desc" id="desc" rows="5" placeholder="其他说明..."><?php echo $data->desc ;?></textarea>
                </div>
                <div class="mui-input-row">
                    <label>状态</label>
                    <input readonly="true" type="text" value="<?php echo $data->reply_status ;?>">
                </div>
            </form>

            <form class="mui-input-group" style="margin-top: 5px;">
            <?php if( ! empty($mark1ReplyData)): ?>
                <?php foreach($mark1ReplyData as $vo): ?>
                <div class="mui-content">
                    <ul class="mui-table-view" style="margin-top: 0px;">
                        <li class="mui-table-view-cell">
                            <div class="mui-table">
                                <div class="mui-table-cell mui-col-xs-10">
                                    <label style="float: left;">
                                        <?php echo $vo['user_name'] ;?>：<?php echo $vo['status']==1 ? '同意' : '拒绝' ;?>
                                    </label>
                                    <label style="float: right;">
                                        <?php echo date('Y-m-d H:i:s', $vo['time']) ;?>
                                    </label>
                                    <p style="float: left;width: 100%;" class="mui-h6 mui-ellipsis">意见：<?php echo $vo['content'] ;?></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php endforeach; ?>
            <?php endif ;?>

            <?php if( ! empty($mark2ReplyData)): ?>
                <div class="mui-content">
                    <ul class="mui-table-view" style="margin-top: 0px;">
                        <li class="mui-table-view-cell">
                            <div class="mui-table">
                                <div class="mui-table-cell mui-col-xs-10">
                                    <label style="float: left;">
                                        <?php echo $mark2ReplyData['user_name'] ;?>：承办
                                    </label>
                                    <label style="float: right;">
                                        <?php echo date('Y-m-d H:i:s', $mark2ReplyData['time']) ;?>
                                    </label>
                                    <p style="float: left;width: 100%;" class="mui-h6 mui-ellipsis">备注：<?php echo $mark2ReplyData['content'] ;?></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endif ;?>

            <?php if( ! empty($mark3ReplyData)): ?>
                <div class="mui-content">
                    <ul class="mui-table-view" style="margin-top: 0px;">
                        <li class="mui-table-view-cell">
                            <div class="mui-table">
                                <div class="mui-table-cell mui-col-xs-10">
                                    <label style="float: left;">
                                        <?php echo $mark3ReplyData['user_name'] ;?>：办理
                                    </label>
                                    <label style="float: right;">
                                        <?php echo date('Y-m-d H:i:s', $mark3ReplyData['time']) ;?>
                                    </label>
                                    <p style="float: left;width: 100%;" class="mui-h6 mui-ellipsis">备注：<?php echo $mark3ReplyData['desc'] ;?></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="mui-input-row">
                    <label>派车日期</label>
                    <input type="hidden" id="chooseDateTxt">
                    <input readonly="true" type="text" value="<?php echo $mark3ReplyData['send_car_date'] ;?>">
                </div>
                <div class="mui-input-row">
                    <label>派车形式</label>
                    <input readonly="true" type="text" value="<?php if($mark3ReplyData['car_type'] == 1){echo '内部用车';}elseif($mark3ReplyData['car_type'] == 2){echo '外部用车';}else{echo '混合';}?>"/>
                </div>

                <?php if( ! empty($mark3ReplyData['car_type1'])): ?>
                    <div id="driver">
                        <h5 style="margin: 18px 0px 3px 16px;">内部车辆</h5>
                        <?php foreach($mark3ReplyData['car_type1'] as $vo): ?>
                            <div class="mui-input-row">
                                <label><?php echo $vo['car_name'] ;?></label>
                                <span style="float: left; width: 15%;padding: 11px 0px;">司机</span>
                                <input readonly="true" style="float:left;width:15%;padding: 11px 0px;" type="text" value="<?php echo $vo['driver'];?>"/>
                                <input readonly="true" style="float:right;width:15%;padding: 11px 0px;" type="number" value="<?php echo $vo['mileage'];?>"/>
                                <span style="float: right; width: 15%;padding: 11px 0px;">里程</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if( ! empty($mark3ReplyData['car_type2'])): ?>
                    <div>
                        <h5 style="margin: 18px 0px 3px 16px;">外部约车（单位：辆）</h5>
                        <?php foreach($mark3ReplyData['car_type2'] as $vo2): ?>
                            <div class="mui-input-row">
                                <label><?php echo $vo2['carType2_name'] ;?></label>
                                <input readonly="true" type="text" value="<?php echo $vo2['carType2_num'] ;?> 辆"/>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if( isset($mark3ReplyData['end_desc'] )):?>
                <div class="mui-input-row" style="margin: 0px 5px;height: 89px;">
                    <h5 style="margin: 11px;">结束备注</h5>
                    <textarea readonly="true" name="desc" rows="5" placeholder="结束备注"><?php echo $mark3ReplyData['end_desc'];?></textarea>
                </div>
                <?php endif; ?>
            <?php endif ;?>
            </form>

            <?php if( $canReply == 1 && $mark == 1 && $data->status == 1): ?>
                <form class="mui-input-group" id="form2">
                    <input type="hidden" name="apply_id" id="apply_id" value="<?php echo $data->id ;?>">
                    <input type="hidden" name="depart_id" id="depart_id" value="<?php echo $data->department_id ;?>">
                    <input type="hidden" name="process_id" value="<?php echo $data->process_id ;?>">
                    <input type="hidden" name="mark" value="<?php echo $mark ;?>">
                    <input type="hidden" name="reply_id" value="<?php echo $replyId ;?>">
                    <input type="hidden" name="use_date" value="<?php echo date('Y-m-d', $data->use_date) ;?>">
                    <input type="hidden" name="member_name" value="<?php echo $data->member_name ;?>">
                    <div class="mui-input-row mui-radio">
                        <label>同意</label>
                        <input name="reply_status" type="radio" checked value="1">
                    </div>
                    <div class="mui-input-row mui-radio">
                        <label>拒绝</label>
                        <input name="reply_status" type="radio" value="2">
                    </div>
                    <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                        <textarea name="reply_content" id="reply_content" rows="5" placeholder="审批意见"></textarea>
                    </div>
                </form>
                <div class="mui-button-row" id="blockbtn">
                    <button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="btn-mark1"">确定</button>
                </div>
            <?php endif; ?>

            <?php if( $canReply == 1 && $mark == 2 && $data->status == 2): ?>
                <form class="mui-input-group" id="form2">
                    <input type="hidden" name="apply_id" id="apply_id" value="<?php echo $data->id ;?>">
                    <input type="hidden" name="depart_id" id="depart_id" value="<?php echo $data->department_id ;?>">
                    <input type="hidden" name="process_id" value="<?php echo $data->process_id ;?>">
                    <input type="hidden" name="next_process_id" value="<?php echo $nextProcessId ;?>">
                    <input type="hidden" name="mark" value="<?php echo $mark ;?>">
                    <input type="hidden" name="reply_id" value="<?php echo $replyId ;?>">
                    <input type="hidden" name="use_date" value="<?php echo date('Y-m-d', $data->use_date) ;?>">
                    <input type="hidden" name="member_name" value="<?php echo $data->member_name ;?>">
                    <input type="hidden" name="reply_status" value="1">
                    <div class="mui-input-row">
                        <label style="width: 40%;">选择承办人</label>
                        <select style="width: 60%;" name="execute_id">
                            <?php foreach($mark3ManageRet as $k=>$vo): ?>
                                <option value="<?php echo $vo->userid ;?>"><?php echo $vo->name ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                        <textarea name="reply_content" id="reply_content" rows="5" placeholder="审批意见"></textarea>
                    </div>
                </form>
                <div class="mui-button-row" id="blockbtn">
                    <button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="btn-mark1"">确定</button>
                </div>
            <?php endif; ?>

            <?php if( $canReply == 1 && $mark == 3 && $data->status == 3 ): ?>
                <form class="mui-input-group" id="form2">
                    <input type="hidden" name="apply_id" id="apply_id" value="<?php echo $data->id ;?>">
                    <input type="hidden" name="depart_id" id="depart_id" value="<?php echo $data->department_id ;?>">
                    <input type="hidden" name="process_id" value="<?php echo $data->process_id ;?>">
                    <input type="hidden" name="mark" value="<?php echo $mark ;?>">
                    <input type="hidden" name="reply_id" value="<?php echo $replyId ;?>">
                    <input type="hidden" name="apply_status" value="<?php echo $data->status ;?>">
                    <div class="mui-input-row">
                        <label>派车形式</label>
                        <select name="car_type" id="car_type">
                            <option value="0">请选择</option>
                            <option value="1">内部车辆</option>
                            <option value="2">外部约车</option>
                            <option value="3">混合</option>
                        </select>
                    </div>

                    <div id="type1" style="display: none;">
                        <h5 style="margin: 18px;">内部车辆</h5>
                        <?php foreach($carsType1 as $k=>$vo): ?>
                            <div class="mui-input-row mui-checkbox mui-left">
                                <label><?php echo $vo->name ;?></label>
                                <input id="cars_ids" name="cars_ids[]" value="<?php echo $vo->id ;?>" type="checkbox">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="type2" style="display: none;">
                        <h5 style="margin: 18px;">外部约车（单位：辆）</h5>
                        <?php foreach($carsType2 as $k=>$vo): ?>
                            <div class="mui-input-row">
                                <label><?php echo $vo->name ;?></label>
                                <div class="mui-numbox">
                                    <button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
                                    <input class="mui-input-numbox" type="number" name="cars_num[<?php echo $vo->id ;?>]" value="0"/>
                                    <button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mui-input-row">
                        <label>派车日期</label>
                        <input type="text" name="send_car_date" id="chooseDateTxt" value="<?php echo date('Y-m-d', time()) ;?>">
                    </div>

                    <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                        <textarea name="desc" id="desc" rows="5" placeholder="备注"></textarea>
                    </div>
                </form>
                <div class="mui-button-row" id="blockbtn">
                    <button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="btn-mark3"">确定</button>
                </div>
            <?php endif; ?>

            <?php if( $canReply == 1 && $mark == 4 && $data->status == 4 ): ?>
                <style>
                    .mui-input-row span {
                        font-family: 'Helvetica Neue',Helvetica,sans-serif;
                        line-height: 1.1;
                        float: left;
                        width: 15%;
                        padding: 11px 0px;
                    }
                </style>
                <form class="mui-input-group" id="form2">
                    <input type="hidden" name="apply_id" id="apply_id" value="<?php echo $data->id ;?>">
                    <input type="hidden" name="depart_id" id="depart_id" value="<?php echo $data->department_id ;?>">
                    <input type="hidden" name="process_id" value="<?php echo $data->process_id ;?>">
                    <input type="hidden" name="mark" value="<?php echo $mark ;?>">
                    <input type="hidden" name="reply_id" value="<?php echo $replyId ;?>">
                    <input type="hidden" name="apply_status" value="<?php echo $data->status ;?>">
                    <?php if( ! empty($carType1Ret)): ?>
                        <div id="driver" style="padding-top: 1px">
                            <h5 style="margin: 18px;">内部车辆</h5>
                            <?php foreach($carType1Ret as $k1=>$vo1): ?>
                                <div class="mui-input-row">
                                    <label><?php echo $vo1->name ;?></label>
                                    <span>司机</span>
                                    <input style="float:left;width:15%;padding: 11px 0px;" type="text" name="driver[<?php echo $vo1->id ;?>]" value="<?php echo $driver ;?>"/>
                                    <span>里程</span>
                                    <input style="float:left;width:15%;padding: 11px 0px;" type="number" name="mileage[<?php echo $vo1->id ;?>]" value="1"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if( ! empty($carType2Ret)): ?>
                        <div style="padding-top: 1px">
                            <h5 style="margin: 18px;">外部约车（单位：辆）</h5>
                            <?php foreach($carType2Ret as $k1=>$vo2): ?>
                                <div class="mui-input-row">
                                    <label><?php echo $vo2['carType2_name'] ;?></label>
                                    <input readonly="true" type="text" value="<?php echo $vo2['carType2_num'] ;?> 辆"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mui-input-row" style="margin: 10px 5px;height: 76px;">
                        <textarea name="desc" rows="5" placeholder="备注"></textarea>
                    </div>
                </form>
                <div class="mui-button-row" id="blockbtn">
                    <button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="btn-mark4"">确定</button>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>
<script src="/static/mui/js/zepto.min.js"></script>
<script src="/static/mui/js/mui.picker.min.js"></script>
<script src="/static/mui/js/mui.date.min.js"></script>
<script>
    mui.init();
    $(function($){
        //提交mark1（审核流程审核） 和mark2按钮 （综合负责人选择承办人）
        $('#btn-mark1').click(function(){
            var reply_status = $('#form2 input[name="reply_status"]:checked ').val();
            var reply_content = $('#reply_content').val();
            if(reply_status == 2 && reply_content == ''){
                mui.alert('请填写拒绝理由');
                return false;
            }

            $.ajax({
                type : "POST",
                url : "/app/car/replyapply",
                data : $('#form2').serialize(),
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        mui.alert(res.message, '提示', function(){
                            window.location.href = '/app/car/reply';
                        });
                    }else{
                        mui.alert(res.message);
                    }
                }
            });
        });

        //选择派车形式，展示对应样式
        $('#car_type').change(function(){
            var type = $('#car_type').val();
            if(type == 0){
                $('#type1').hide();
                $('#type2').hide();
            }else if(type == 1){
                $('#type1').show();
                $('#type2').hide();
            }else if(type == 2){
                $('#type1').hide();
                $('#type2').show();
            }else{
                $('#type1').show();
                $('#type2').show();
            }
        })

        //提交mark3按钮 (承办人选择派车形式，派车时间，填写车辆信息)
        $('#btn-mark3').click(function(){
            var type = $('#car_type').val();
            var cars_ids = $("input[type='checkbox']:checked").length;
            if(type == 0){
                mui.alert('请选择派车形式');
                return false;
            }
            if(type == 1 && cars_ids < 1){
                mui.alert('请选择内部车辆');
                return false;
            }
            //如果是外部约车
            if(type == 2){
                var num = 0;
                $("#type2 input[type='number']").each(function(){
                    num += $(this).val()*1
                });
                if(num < 1){
                    mui.alert('请选择外部车辆');
                    return false;
                }
            }
            $.ajax({
                type : "POST",
                url : "/app/car/replyapply",
                data : $('#form2').serialize(),
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        mui.alert(res.message, '提示', function(){
                            window.location.href = res.extension;
                        });
                    }else{
                        mui.alert(res.message);
                    }
                }
            });
        });

        //提交mark4按钮(用车完成。承办人设置司机，填写里程)
        $('#btn-mark4').click(function(){
            var driver_count = 1;
            $("#driver input[type='text']").each(function(){
                if($(this).val().length == 0){
                    driver_count = 0;
                }
            });
            if(driver_count == 0){
                mui.alert('请填写司机');
                return false;
            }
            var mileage_count = 1;
            $("#driver input[type='number']").each(function(){
                if($(this).val() == 0){
                    mileage_count = 0;
                }
            });
            if(mileage_count == 0){
                mui.alert('请填写里程');
                return false;
            }

            $.ajax({
                type : "POST",
                url : "/app/car/replyapply",
                data : $('#form2').serialize(),
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        mui.alert(res.message, '提示', function(){
                            window.location.href = '/app/car/reply';
                        });
                    }else{
                        mui.alert(res.message);
                    }
                }
            });
        });

    });

</script>
