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
                    <a href="/home/user">企业用户管理</a>
                </li>
                <li class="active">添加企业用户</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    添加企业用户
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">

                    <form class="form-horizontal" id="validation-form">
                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="user_name">用户名:</label>

                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="user_name" name="user_name" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="password">密码:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="password" id="password" name="password" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="password2">确认密码:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="password" id="password2" name="password2" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="qy_ids">所管理的企业微信号:</label>

                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <select multiple="multiple" class="select2 tag-input-style" data-placeholder="选择企业微信号">
                                        <?php foreach($qyChatData as $v): ?>
                                            <option value="<?php echo $v['id']?>"><?php echo $v['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="qy_ids" name="qy_ids" >
                        </div>
                        <div class="space-2"></div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="email">邮箱:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="email" name="email" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="nick_name">昵称:</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <input type="text" id="nick_name" name="nick_name" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_secret"> 状态 </label>

                            <div class="radio col-sm-8">
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

                    </form>
                    <div class="clearfix">
                        <div class="col-md-offset-1 col-md-9">
                            <button class="btn btn-info" type="button" id="btn">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <a class="btn" href="/home/user">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                返回
                            </a>
                        </div>
                    </div>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
{include file="layout/datatable" /}
<script>
    $(function($){
        $('.select2').css('width','228').select2({
            allowClear:true
        }).on('change', function(e){
            //select2选中的值
            $("#qy_ids").val(e.val);
        });

        $('#btn').click(function(){
            $.ajax({
                type : "POST",
                url : "/home/user/add",
                data : $('#validation-form').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/home/user/index';
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
