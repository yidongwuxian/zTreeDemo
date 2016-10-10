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
                <li class="active">车辆管理</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header align-right">
                <a class="btn btn-primary" href="/home/car/carsadd">添加车辆</a>
            </div>

            <div class="row">
                <div class="col-xs-12">

                    <form class="form-horizontal" id="validation-form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="type" > 状态:</label>

                            <div class="radio col-sm-10">
                                <label>
                                    <input type="radio" checked="" value="1" class="ace" name="type" id="nei">
                                    <span class="lbl"> 内部车辆</span>
                                </label>
                                <label>
                                    <input type="radio" value="2" class="ace" name="type" id="wai">
                                    <span class="lbl"> 外部约车</span>
                                </label>
                                <b style="padding-left: 20px; display: none;" id="tip">提示:外部约车只需填写名称</b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="name">名称:</label>

                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input type="text" id="name" name="name" class="col-xs-12 col-sm-3" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="firm">车辆品牌:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input type="text" id="firm" name="firm" class="col-xs-12 col-sm-3" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="idcard">车牌号:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input type="text" id="idcard" name="idcard" class="col-xs-12 col-sm-3" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="qy_ids">描述:</label>
                            <div class="col-sm-10">
                                <div class="clearfix">
                                    <input type="text" id="desc" name="desc" class="col-xs-12 col-sm-3" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"> 状态 </label>

                            <div class="radio col-sm-10">
                                <label>
                                    <input name="status" type="radio" class="ace" value="1" checked/>
                                    <span class="lbl"> 启用</span>
                                </label>
                                <label>
                                    <input name="status" type="radio" class="ace" value="2"/>
                                    <span class="lbl"> 禁用</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right" for="corp_secret"></label>
                            <div class="col-md-10">
                                <button class="btn btn-info" type="button" id="btn">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </button>
                                <a class="btn" href="/home/car/carlist">
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
        //提示信息的展示和隐藏
        $('#nei').click(function(){$('#tip').hide()});
        $('#wai').click(function(){$('#tip').show()});

        $('#btn').click(function(){
            //if(!$('#validation-form').valid()) return false;
            $.ajax({
                type : "POST",
                url : "/home/car/caradd",
                data : $('#validation-form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/home/car/carlist';
                        });
                    }else{
                        bootbox.alert(res.message,function(){
                        });
                    }
                }
            });

        });

    });
</script>
