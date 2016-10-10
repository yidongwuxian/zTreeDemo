<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="/home/index">主页</a>
                </li>

                <li>
                    <a href="/home/user">应用管理</a>
                </li>
                <li>
                    <a href="">用车申请</a>
                </li>
                <li class="active">详情</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">

            <div class="row">
                <div class="col-xs-12">

                    <form class="form-horizontal" id="validation-form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">申请人:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['member_name']?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">用车日期:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo date('Y-m-d',$data['use_date']) ;?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">用车时段:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['use_time']?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">人数:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['person_num']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">用车数:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['cars_num']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">联系方式:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['mobile']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">出发地:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['start_city']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">目的地:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['end_city']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">事由:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['reason']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">其他说明:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['desc']?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">状态:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $data['reply_status']?>"/>
                                </div>
                            </div>
                        </div>
                        <?php if( ! empty($mark1ReplyData)): ?>
                            <?php foreach($mark1ReplyData as $vo): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-padding-right"><?php echo $vo['process_name']?>:</label>
                                    <div class="col-sm-10">
                                        <div class="clearfix">
                                            <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $vo['user_name'];?>&nbsp;&nbsp;&nbsp;<?php echo($vo['status']==1) ? '同意' : '拒绝';?>&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s',$vo['time']);?>"/>
                                            <label class="col-sm-1 control-label no-padding-right" >审批意见:&nbsp;&nbsp;&nbsp;</label>
                                            <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $vo['content']?>"/>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if( ! empty($mark2ReplyData)): ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right"><?php echo $mark2ReplyData['process_name'];?>:</label>
                                <div class="col-sm-10">
                                    <div class="clearfix">
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark2ReplyData['user_name'];?>&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s',$mark2ReplyData['time']);?>"/>
                                        <label class="col-sm-1 control-label no-padding-right" >办理意见:&nbsp;&nbsp;&nbsp;</label>
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark2ReplyData['content']?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if( ! empty($mark3ReplyData)): ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right"><?php echo $mark3ReplyData['process_name'];?>:</label>
                                <div class="col-sm-10">
                                    <div class="clearfix">
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark3ReplyData['user_name'];?>&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s',$mark3ReplyData['time']);?>"/>
                                        <label class="col-sm-1 control-label no-padding-right" >备注:&nbsp;&nbsp;&nbsp;</label>
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark3ReplyData['desc']?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right">派车形式:</label>
                                <div class="col-sm-10">
                                    <div class="clearfix">
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo ($mark3ReplyData['car_type'] == 1) ? '内部用车' : (($mark3ReplyData['car_type'] == 2) ? '外部约车' : '混合');?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right">派车时间:</label>
                                <div class="col-sm-10">
                                    <div class="clearfix">
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark3ReplyData['send_car_date'];?>"/>
                                    </div>
                                </div>
                            </div>


                            <?php if( ! empty($mark3ReplyData['car_type1'])): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-padding-right">内部车辆:</label>
                                    <div class="col-sm-10">
                                        <?php foreach($mark3ReplyData['car_type1'] as $vo1): ?>
                                        <div class="clearfix">
                                            <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $vo1['car_name'] ;?>&nbsp;&nbsp;&nbsp;司机：<?php echo $vo1['driver'];?>&nbsp;&nbsp;&nbsp;里程：<?php echo $vo1['mileage'];?>"/>

                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if( ! empty($mark3ReplyData['car_type2'])): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-padding-right">外部约车:</label>
                                    <div class="col-sm-10">
                                        <?php foreach($mark3ReplyData['car_type2'] as $vo2): ?>
                                            <div class="clearfix">
                                                <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="名称：<?php echo $vo2['carType2_name'] ;?>&nbsp;&nbsp;&nbsp;数量：<?php echo $vo2['carType2_num'] ;?> 辆"/>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right">结束备注:</label>
                                <div class="col-sm-10">
                                    <div class="clearfix">
                                        <input readonly="true" type="text" class="col-xs-12 col-sm-3" value="<?php echo $mark3ReplyData['end_desc'];?>"/>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"></label>
                            <div class="col-md-10">
                                <button class="btn btn-info" type="button" id="btn">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    确定
                                </button>
                                <a class="btn" href="/home/car/apply">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    返回
                                </a>
                            </div>
                        </div>
                    </form>


                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
{include file="layout/datatable" /}
<script>
    $(function($){
        $('#btn').click(function(){
            window.location.href = '/home/car/apply';
        });
    });
</script>