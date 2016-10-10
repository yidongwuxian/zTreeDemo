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
                <li class="active">车辆管理</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header align-right">
                <a class="btn btn-primary" href="/home/car/caradd">添加车辆</a>
            </div>

            <div class="widget-box widget-color-blue2">

            <div class="widget-header">
                <h4 class="widget-title lighter smaller">车辆列表</h4>
            </div>
            <div class="widget-body">



            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <table id="simple-table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>车辆类型</th>
                                <th>名称</th>
                                <th>车辆品牌</th>
                                <th>车牌号</th>
                                <th>描述</th>
                                <th>状态</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if( ! empty($list)): ?>
                                <?php foreach($list as $value): ?>
                                <tr>
                                    <td><?php echo $value->type == 1 ? '内部车辆' : '外部约车'; ?></td>
                                    <td><?php echo $value->name; ?></td>
                                    <td><?php echo $value->firm; ?></td>
                                    <td><?php echo $value->idcard; ?></td>
                                    <td><?php echo $value->desc; ?></td>
                                    <?php if($value->status == 1): ?>
                                              <td><span class="label label-sm label-success">可用</span></td>
                                    <?php else : ?>
                                              <td><span class="label label-sm label-warning">不可用</span></td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a class="btn btn-xs btn-info" href="/home/car/caredit/id/<?php echo $value->id; ?>">
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
            bootbox.confirm("确定要删除吗？", function(result) {
                if(result) {
                    var data = {};
                    data.id = id;
                    $.ajax({
                        type : "POST",
                        url : "/home/car/cardel",
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