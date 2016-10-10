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
                    <a href="/ldd/qychat">企业号管理</a>
                </li>
                <li class="active">编辑企业号</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    编辑企业号
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" id="qychat">
                        <input type="hidden" name="id" value="<?php echo $qychat->id; ?>">
                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_name"> 企业号名称 </label>

                            <div class="col-sm-10">
                                <input type="text" id="corp_name" name="corp_name" placeholder="企业号名称" class="col-xs-10 col-sm-5" value="<?php echo $qychat->name; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="chat_str"> 简称 </label>

                            <div class="col-sm-10">
                                <input type="text" id="chat_str" name="chat_str" placeholder="简称" class="col-xs-10 col-sm-5" value="<?php echo $qychat->chat_str; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_id"> CorpID </label>

                            <div class="col-sm-10">
                                <input type="text" id="corp_id" name="corp_id" placeholder="CorpID" class="col-xs-10 col-sm-5" value="<?php echo $qychat->corp_id; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_secret"> CorpSecret </label>

                            <div class="col-sm-10">
                                <input type="text" id="corp_secret" name="corp_secret" placeholder="CorpSecret" class="col-xs-10 col-sm-5" value="<?php echo $qychat->corp_secret; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_secret"> 状态 </label>

                            <div class="radio col-sm-8">
                                <label>
                                    <input name="status" type="radio" class="ace" value="1" <?php if($qychat->status == 1) echo 'checked'; ?>/>
                                    <span class="lbl"> 启用</span>
                                </label>
                                <label>
                                    <input name="status" type="radio" class="ace" value="2" <?php if($qychat->status == 2) echo 'checked'; ?>/>
                                    <span class="lbl"> 禁用</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label no-padding-right" for="corp_secret"></label>
                            <div class="col-sm-10">
                                <a class="btn btn-info" id="sub">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    提交
                                </a>
                                <a class="btn" href="/home/qychat">
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
<script>
    $(function(){
        $('#sub').click(function(){

            $.ajax({
                type : "POST",
                url : "/home/qychat/edit",
                data : $('#qychat').serialize(),
                dataType: "json",
                success : function(res){ //res:{1:成功}
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = '/home/qychat';
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
