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
                    <a href="#">企业用户管理</a>
                </li>
                <li class="active">企业用户列表</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header align-right">
                <a class="btn btn-primary" href="/home/user/add">添加企业用户</a>
            </div><!-- /.page-header -->

            <div class="widget-box widget-color-blue2">

            <div class="widget-header">
                <h4 class="widget-title lighter smaller">企业用户列表</h4>
            </div>
            <div class="widget-body">



            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <table id="simple-table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>状态</th>
                                <th>所管理的企业微信号</th>
                                <th class="hidden-480">创建日期</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if( ! empty($list)): ?>
                                <?php foreach($list as $qychat): ?>
                                <tr>
                                    <td><?php echo $qychat->user_name; ?></td>
                                    <td><?php echo $qychat->nick_name; ?></td>
                                    <td><?php echo $qychat->status == 1 ? '启用' : '禁用'; ?></td>
                                    <td><?php echo $qychat->qy_name; ?></td>
                                    <td class="hidden-480"><?php echo date('Y-m-d',$qychat->create_time); ?></td>
                                    <td class="hidden-480">
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a class="btn btn-xs btn-info" href="/home/user/edit/id/<?php echo $qychat->id; ?>">
                                                <i class="ace-icon fa fa-pencil bigger-120"> 编辑 </i>
                                            </a>

                                            <a class="btn btn-xs btn-danger" data-id="<?php echo $qychat->id; ?>" data-type="delete">
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
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
{include file="layout/datatable" /}
<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
        //数据表格
        $('#simple-table').dataTable( {
            bAutoWidth: false,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
            "bStateSave": true, //状态保存
            "bProcessing": true,
            "bDestroy":true,
            "bJQueryUI": true,
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
                },
                "oAria": {
                    "sSortAscending": ": 以升序排列此列",
                    "sSortDescending": ": 以降序排列此列"
                }
            }
        } );

        //删除
        $("[data-type='delete']").click(function(){
            var id = $(this).data('id');
            bootbox.confirm("确定要删除该企业用户吗？", function(result) {
                if(result) {
                    var data = {};
                    data.id = id;
                    $.ajax({
                        type : "POST",
                        url : "/home/user/delete",
                        data : data,
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
                }
            });

        });
    })
</script>