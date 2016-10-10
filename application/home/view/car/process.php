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
        <div class="tabbable">

            <style type="text/css">#dynamic-table tr > td:first-child, #dynamic-table tr > th:first-child{padding-left: 1%;}</style>
            <div class="page-content">
                <div class="page-header align-right">
                    <a class="btn btn-primary" href="/home/car/processadd">添加流程</a>
                </div>
                <ul id="myTab4" class="nav nav-tabs padding-12 tab-color-blue background-blue" style="padding-left:20px;padding-right: 20px;">
                    <li <?php if($mark == 1) echo 'class="active"'; ?>>
                        <a href="/home/car/process/mark/1" aria-expanded="false">审核流程</a>
                    </li>
                    <li <?php if($mark == 2) echo 'class="active"'; ?>>
                        <a href="/home/car/process/mark/2" aria-expanded="true">派发流程</a>
                    </li>
                    <li <?php if($mark == 3) echo 'class="active"'; ?>>
                        <a href="/home/car/process/mark/3" aria-expanded="true">办理流程</a>
                    </li>
                </ul>
                <div class="widget-box widget-color-blue2">

                    <div class="widget-header">
                        <h4 class="widget-title lighter smaller"><?php if($mark == 1){echo "审核流程";}elseif($mark == 2){echo "派发任务";}else{echo "办理流程";} ;?></h4>
                    </div>
                    <div class="widget-body">

                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>流程顺序</th>
                                            <th>流程节点名称</th>
                                            <th>创建时间</th>
                                            <?php if($mark >1 ):?>
                                                <th>配置人员</th>
                                            <?php endif;?>
                                            <th>操作</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php if( ! empty($list)): ?>
                                            <?php foreach($list as $value): ?>
                                                <tr>
                                                    <td><?php echo $value->order; ?></td>
                                                    <td><?php echo $value->name; ?></td>
                                                    <td><?php echo date('Y-m-d H:i', $value->create_time); ?></td>
                                                    <?php if($mark >1 ):?>
                                                        <td><?php echo isset($value->member_name) ? implode(',',$value->member_name) : ''; ?></td>
                                                    <?php endif;?>
                                                    <td>
                                                        <div class="hidden-sm hidden-xs btn-group">
                                                            <?php if($mark == 2):?>
                                                                <a class="btn btn-success btn-xs setsend" data-id="<?php echo $value->id; ?>" data-manage-id="<?php echo isset($value->manage_id) ? $value->manage_id : '' ; ?>" data-member-id="<?php echo isset($value->member_id) ? $value->member_id : ''; ?>">
                                                                    <i class="ace-icon fa fa-wrench  bigger-120 icon-only"> 配置人员 </i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if($mark == 3):?>
                                                                <a class="btn btn-success btn-xs setexecute"  data-id="<?php echo $value->id; ?>" data-manage-id="<?php echo isset($value->manage_id) ? $value->manage_id : '' ; ?>" data-member-id="<?php echo isset($value->member_id) ? $value->member_id : '';?>">
                                                                    <i class="ace-icon fa fa-wrench  bigger-120 icon-only"> 配置人员 </i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <a class="btn btn-xs btn-info" href="/home/car/processedit/id/<?php echo $value->id; ?>">
                                                                <i class="ace-icon fa fa-pencil bigger-120"> 编辑 </i>
                                                            </a>

                                                            <a class="btn btn-xs btn-danger" data-id="<?php echo $value->id; ?>" data-type="delete">
                                                                <i class="ace-icon fa fa-trash-o bigger-120"> 删除 </i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.row -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.main-content -->
<script src="/static/base/js/choosePerson.js"></script>
{include file="layout/tree" /}
{include file="layout/datatable" /}
<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
        //数据表格
        $('#dynamic-table').dataTable( {
            bAutoWidth: false,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
            "bStateSave": true, //状态保存
            "bProcessing": true,
            "bDestroy":true,
            "bJQueryUI": false,
            "sPaginationType": "full_numbers",
            "bInfo": false,//页脚信息
            "sServerMethod": "GET",
            language: {
                "sProcessing": "处理中...",
                "sLengthMenu": "每页显示 _MENU_ 项结果",
                "sZeroRecords": "没有匹配结果",
                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "（由 _MAX_ 项结果过滤）",
                "sInfoPostFix": "",
                "sSearch": "搜索：",
                "sUrl": "",
                "sEmptyTable": "表中数据为空",
                "sLoadingRecords": "载入中...",
                "sInfoThousands": ",",
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": "上一页",
                    "sNext": "下一页",
                    "sLast": "末页"
                }
            }
        } );

        //删除
        $("[data-type='delete']").click(function(){
            var id = $(this).data('id');
            bootbox.confirm("确定要删除吗？", function(result) {
                if(result) {
                    var data = {};
                    data.id = id;
                    $.ajax({
                        type : "POST",
                        url : "/home/car/processdel",
                        data : data,
                        dataType: "json",
                        success : function(res){
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
                }
            });

        });

        //点击配置人员按钮，获取要配置的流程节点id(process_id)和管理信息id
        var id = null;
        var process_id = null;
        $("#dynamic-table").on('click', 'a.setsend',  function(){
            id = $(this).attr('data-manage-id');
            process_id = $(this).attr('data-id');
        });

        $("#dynamic-table").on('click', 'a.setexecute',  function(){
            id = $(this).attr('data-manage-id');
            process_id = $(this).attr('data-id');
        });

        var _send = new choosePerson('.setsend', {
            chooseType: 'single',
            onsubmit: function(values,type){
                if(type == 'single'){
                    //如果没有选中用户，提示错误
                    if( typeof(values.val) == "undefined" ){
                        bootbox.alert('请选择一个用户');
                        return false;
                    }
                    //设置管理员
                    $.ajax({
                        type : "POST",
                        url : "/home/car/setmanage/",
                        data : {
                            id : id,
                            member_id : values.val,
                            depart_id : 0,
                            process_id : process_id
                        },
                        dataType: "json",
                        success : function(res){
                            if(res.result == '1'){
                                bootbox.alert(res.message,function(){
                                    //关闭当前弹出框，刷新当前页面
                                    $("#set_choose_manage").css({display: "none","aria-hidden":"true"});
                                    window.location.reload();
                                });
                            }else{
                                bootbox.alert(res.message,function(){
                                });
                            }
                        }
                    });
                }

            }
        });

        var _execute = new choosePerson('.setexecute',
            {
                chooseType: 'multi',
                onsubmit: function(values,type){
                    //console.log(values);return false;
                    var member_ids = '';
                    $.each( values, function( i , n ){
                        member_ids += n.val+',';
                    });
                    member_ids = member_ids.substring(0,member_ids.length-1);
                    if(type == 'multi'){
                        //如果没有选中用户，提示错误
                        if( values.length < 1 ){
                            bootbox.alert('请至少选择一个用户');
                            return false;
                        }
                        //设置管理员
                        $.ajax({
                            type : "POST",
                            url : "/home/car/setmanage/",
                            data : {
                                id : id,
                                member_id : member_ids,
                                depart_id : 0,
                                process_id : process_id
                            },
                            dataType: "json",
                            success : function(res){
                                if(res.result == '1'){
                                    bootbox.alert(res.message,function(){
                                        //关闭当前弹出框，刷新当前页面
                                        $("#set_choose_manage").css({display: "none","aria-hidden":"true"});
                                        window.location.reload();
                                    });
                                }else{
                                    bootbox.alert(res.message,function(){
                                    });
                                }
                            }
                        });
                    }
                }
            });
    })
</script>