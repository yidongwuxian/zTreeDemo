<div id="set_send_manage" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="width: 720px">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    通讯录
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12" style="padding-left: 17px;padding-right: 17px;">

                    <div class="row">
                        <div class="col-sm-3" style="width: 35%;padding-right: 0px;">
                            <div class="widget-box widget-color-blue2">
                                <div class="widget-header">
                                    <h4 class="widget-title lighter smaller">部门</h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <ul id="department_tree" class="ztree"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9" style="width: 65%;padding-left: 5px;">
                            <div class="widget-box widget-color-blue2">
                                <div class="widget-header">
                                    <h4 class="widget-title lighter smaller">人员列表</h4>
                                </div>
                                <div class="widget-body">
                                    <table id="simple-table2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>姓名</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <script type="text/javascript">
                        var $assets = "dist";
                    </script>


                </div>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="member_submit">
                    <i class="ace-icon fa fa-check"></i>
                    确定
                </a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    取消
                </a>
            </div>
        </div>
    </div>
</div>

<!-- inline scripts related to this page -->
<SCRIPT type="text/javascript">
    <!--
    var curMenu = null, zTree_Menu = null;
    var setting2 = {
        view: {
            showLine: false,
            showIcon: false,
            selectedMulti: false,
            dblClickExpand: false,
            addDiyDom: addDiyDom2
        },
        edit: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeClick: beforeClick2
        }
    };


    function addDiyDom2(treeId, treeNode) {
        var spaceWidth = 5;
        var switchObj = $("#" + treeNode.tId + "_switch"),
            icoObj = $("#" + treeNode.tId + "_ico");
        switchObj.remove();
        icoObj.before(switchObj);

        if (treeNode.level > 1) {
            var spaceStr = "<span style='display: inline-block;width:" + (spaceWidth * treeNode.level)+ "px'></span>";
            switchObj.before(spaceStr);
        }
    }

    function beforeClick2(treeId, treeNode) {
        //动态加载数据
        var currentDepartId = treeNode.department_id;
        initTable2(currentDepartId);
    }

    //初始化DataTables
    function initTable2(department_id) {
        //通讯录成员表格
        var oTable = $('#simple-table2')
            .wrap("<div class='dataTables_borderWrap' />")
            .dataTable({
                "bAutoWidth": false,
                "bLengthChange": false,
                "bFilter": true,
                "bSort": false,
                "bStateSave": true, //状态保存
                "bProcessing": true,
                "bDestroy":true,
                "bJQueryUI": false,
                "sPaginationType": "_numbers",
                "bInfo": false,//页脚信息
                "sAjaxSource": '/home/car/getmemberlist/departmentId/' + department_id,
                "sServerMethod": "GET",
                "oLanguage": {
                    "sLengthMenu": "每页显示 _MENU_条",
                    "sZeroRecords": "没有找到符合条件的数据",
                    "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                    "sInfoEmpty": "没有记录",
                    "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                    "sSearch": "搜索：",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "前一页",
                        "sNext": "后一页",
                        "sLast": "尾页"
                    }
                }
            });
    }

    $(document).ready(function(){

        var treeObj = $("#department_tree");
        treeObj.addClass("showIcon");
        var zNodes = null;
        $.ajax({
            type: "GET",
            url: "/home/department/getdepartmenttree",
            dataType: "json",
            success: function (res) { //res:{1:成功}
                zNodes = res;
                $.fn.zTree.init(treeObj, setting2, zNodes);
            }
        });

        //初始化Datatables
        //initTable2(1);

        treeObj.hover(function () {
            if (!treeObj.hasClass("showIcon")) {
                treeObj.addClass("showIcon");
            }
        }, function() {
        });

        //点击配置人员按钮，获取要配置的流程节点id
        var id = null;
        //var member_id = null;
        var process_id = null;
        $("#dynamic-table").on('click', 'a.setsend',  function(){
            //每次点击配置人员，都是删除上一次的选中状态，并刷新列表
            $("#department_tree .curSelectedNode").removeClass("curSelectedNode");
            initTable2(1);
            id = $(this).attr('data-manage-id');
            process_id = $(this).attr('data-id');
            //member_id = $(this).attr('data-member-id');
        });

        //设置部门管理员
        $('#member_submit').click(function(){
            var member_id = $("input[name='member_id']:checked").val();
            //派发流程和办理流程 所设置的人员所属部门默认为0
            var depart_id = 0;
            //如果没有选中用户，提示错误
            if( typeof(member_id) == "undefined" ){
                bootbox.alert('请选择一个用户');
                return false;
            }
            //设置管理员
            $.ajax({
                type : "POST",
                url : "/home/car/setmanage/",
                data : {
                    id : id,
                    member_id : member_id,
                    depart_id : depart_id,
                    process_id : process_id
                },
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            //关闭当前弹出框，刷新当前页面
                            $("#set_send_manage").css({display: "none","aria-hidden":"true"});
                            window.location.reload();
                        });
                    }else{
                        bootbox.alert(res.message,function(){
                        });
                    }
                }
            });
        });

    });


</SCRIPT>