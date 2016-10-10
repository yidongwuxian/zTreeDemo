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
                    <input type="hidden" id="chooseDateTxt">
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
                    <input readonly="true" type="text" value="<?php if($mark3ReplyData['car_type'] == 1){echo '内部用车';}elseif($mark3ReplyData['car_type'] == 2){echo '外部约车';}else{echo '混合';}?>"/>
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
                <div class="mui-input-row" style="margin: 0px 5px;height: 89px;">
                    <h5 style="margin: 11px;">结束备注</h5>
                    <textarea readonly="true" rows="5" placeholder="结束备注"><?php echo $mark3ReplyData['end_desc'] ;?></textarea>
                </div>
            <?php endif ;?>
            </form>

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

    });

</script>
