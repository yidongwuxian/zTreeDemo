<div id="member-add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    创建成员
                </div>
            </div>

            <div class="modal-body padding-14">
                <form class="form-horizontal" role="form" id="member-add-form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="name"> 姓名 </label>
                        <div class="col-sm-10">
                            <input type="text" id="name" name="name" placeholder="姓名" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="userid"> 账号 </label>
                        <div class="col-sm-10">
                            <input type="text" id="userid" name="userid" placeholder="账号" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="gender"> 性别 </label>

                        <div class="radio col-sm-8">
                            <label>
                                <input name="gender" type="radio" class="ace" value="1" checked/>
                                <span class="lbl"> 男</span>
                            </label>
                            <label>
                                <input name="gender" type="radio" class="ace" value="2"/>
                                <span class="lbl"> 女</span>
                            </label>
                        </div>
                    </div>
                    <div class="hr hr-dotted"></div>
                        <label class="col-sm-2 control-label no-padding-right"></label>
                        <h5 class="lighter block red2">微信号、手机、邮箱请至少填写一项！</h5>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="weixinid"> 微信号 </label>
                        <div class="col-sm-10">
                            <input type="text" id="weixinid" name="weixinid" placeholder="微信号" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="mobile"> 手机 </label>
                        <div class="col-sm-10">
                            <input type="text" id="mobile" name="mobile" placeholder="手机" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="email"> 邮箱 </label>
                        <div class="col-sm-10">
                            <input type="text" id="email" name="email" placeholder="邮箱" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="department_ids"> 所属部门 </label>
                        <div class="col-sm-8">
                            <select multiple="" class="chosen-select form-control tag-input-style" id="department_ids" name="department[]">
                                <?php echo $departmentSelectStr; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="member_submit">
                    <i class="ace-icon fa fa-check"></i>
                    添加
                </a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal" id="btn_add_cancel">
                    <i class="ace-icon fa fa-times"></i>
                    取消
                </a>
            </div>
        </div>
    </div>
</div>

<div id="member-edit-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    更新成员
                </div>
            </div>

            <div class="modal-body padding-14">
                <form class="form-horizontal" role="form" id="member-edit-form">
                    <input type="hidden" value="" id="em_id" name="id">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="name"> 姓名 </label>
                        <div class="col-sm-10">
                            <input type="text" id="em_name" name="name" placeholder="姓名" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="userid"> 账号 </label>
                        <div class="col-sm-10">
                            <input type="text" id="em_userid" name="userid" placeholder="账号" class="col-xs-10 col-sm-10" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="gender"> 性别 </label>

                        <div class="radio col-sm-8">
                            <label>
                                <input data-name="em_gender" name="gender" type="radio" class="ace" value="1"/>
                                <span class="lbl"> 男</span>
                            </label>
                            <label>
                                <input data-name="em_gender" name="gender" type="radio" class="ace" value="2"/>
                                <span class="lbl"> 女</span>
                            </label>
                        </div>
                    </div>
                    <div class="hr hr-dotted"></div>
                    <label class="col-sm-2 control-label no-padding-right"></label>
                    <h5 class="lighter block red2">微信号、手机、邮箱请至少填写一项！</h5>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="weixinid"> 微信号 </label>
                        <div class="col-sm-10">
                            <input type="text" id="em_weixinid" name="weixinid" placeholder="微信号" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="mobile"> 手机 </label>
                        <div class="col-sm-10">
                            <input type="text" id="em_mobile" name="mobile" placeholder="手机" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="email"> 邮箱 </label>
                        <div class="col-sm-10">
                            <input type="text" id="em_email" name="email" placeholder="邮箱" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="department_ids"> 所属部门 </label>
                        <div class="col-sm-8">
                            <select multiple="" class="chosen-select form-control tag-input-style" id="em_department_ids" name="department[]">
                                <?php echo $departmentSelectStr; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="member_edit_submit">
                    <i class="ace-icon fa fa-check"></i>
                    更新
                </a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    取消
                </a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#member_submit').click(function(){
        $.ajax({
            type : "POST",
            url : "/home/member/add",
            data : $('#member-add-form').serialize(),
            dataType: "json",
            success : function(res){ //res:{1:成功}
                if(res.result == '1'){
                    bootbox.alert(res.message,function(){
                        window.location.reload();
                    });
                }else{
                    bootbox.alert(res.message,function(){

                    });
                }
            }
        });
    });

    $('#member_edit_submit').click(function(){
        $.ajax({
            type : "POST",
            url : "/home/member/edit",
            data : $('#member-edit-form').serialize(),
            dataType: "json",
            success : function(res){ //res:{1:成功}
                if(res.result == '1'){
                    bootbox.alert(res.message,function(){
                        window.location.reload();
                    });
                }else{
                    bootbox.alert(res.message,function(){

                    });
                }
            }
        });
    });

    //创建用户时点击取消按钮
    $("#btn_add_cancel").click(function(){
        //表单重置
        $("#member-add-form")[0].reset();
        //多项选择器重置
        $("#department_ids").chosen('destroy').val([]).chosen({allow_single_deselect:true}).each(function() {
            var $this = $(this);
            $this.next().css({'width': '100%'});
        });
    });
</script>
