
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">主页</a>
                </li>

                <li>
                    <a href="">应用管理</a>
                </li>
                <li>
                    <a href="">用车申请</a>
                </li>
                <li class="active">流程管理</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>修改流程</h1>
            </div>

            <div class="row">
                <div class="col-xs-12">

                    <form class="form-horizontal" id="form">
                        <input type="hidden" name="id" value="<?php echo $data->id;?>">
                        <input type="hidden" name="old_order_id" value="<?php echo $data->order;?>">
                        <input type="hidden" name="old_mark_id" value="<?php echo $data->mark;?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="type" >流程标记:</label>
                            <div class="radio col-sm-10">
                                <label>
                                    <input type="radio" value="1" class="ace" name="mark" <?php if($data->mark == 1) echo 'checked';?> >
                                    <span class="lbl"> 审核流程</span>
                                </label>
                                <label>
                                    <input type="radio" value="2" class="ace" name="mark" <?php if($data->mark == 2) echo 'checked';?>>
                                    <span class="lbl"> 派发流程</span>
                                </label>
                                <label>
                                    <input type="radio" value="3" class="ace" name="mark" <?php if($data->mark == 3) echo 'checked';?>>
                                    <span class="lbl"> 办理流程</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">流程节点名称:</label>

                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input type="text" id="name" name="name" class="col-xs-12 col-sm-3" value="<?php echo $data->name;?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">流程顺序:</label>
                            <div class="col-sm-10" style="padding-left: 7px;">
                                <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                                    <input id="spinner" name="order" type="text" value="<?php echo $data->order;?>"/>
                                </span>
                                <b>提示:流程按从小到大的顺序排列</b>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"></label>
                            <div class="col-sm-10">
                                <a class="btn btn-info" id="btn">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </a>
                                <a class="btn" href="/home/car/process/mark/<?php echo $data->mark;?>">
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
<script src="/static/base/js/jquery-ui.min.js"></script>
<script>
    $(function($){
        $('#btn').click(function(){
            $.ajax({
                type : "POST",
                url : "/home/car/processedit",
                data : $('#form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/home/car/process/mark/'+res.extension;
                        });
                    }else{
                        bootbox.alert(res.message,function(){
                        });
                    }
                }
            });

        });

        //spinner
        var spinner = $( "#spinner" ).spinner({
            min: 1,
            create: function( event, ui ) {
                //add custom classes and icons
                $(this)
                    .next().addClass('btn btn-success').html('<i class="ace-icon fa fa-plus"></i>')
                    .next().addClass('btn btn-danger').html('<i class="ace-icon fa fa-minus"></i>')

                //larger buttons on touch devices
                if('touchstart' in document.documentElement)
                    $(this).closest('.ui-spinner').addClass('ui-spinner-touch');
            }
        });


    });
</script>
