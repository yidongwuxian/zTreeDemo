<div id="set_execute_manage" class="modal fade" tabindex="-1">
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
                                        <ul id="department_tree3" class="ztree"></ul>
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
                                    <table id="simple-table3" class="table table-striped table-bordered table-hover">
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
                <a class="btn btn-sm btn-primary" id="member_submit3">
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
    var setting3 = {
        view: {
            showLine: false,
            showIcon: false,
            selectedMulti: false,
            dblClickExpand: false,
            addDiyDom: addDiyDom3
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
            beforeClick: beforeClick3
        }
    };

    function addDiyDom3(treeId, treeNode) {
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

    function beforeClick3(treeId, treeNode) {
        //动态加载数据
        var currentDepartId = treeNode.department_id;
        initTable3(currentDepartId);
    }

    //初始化DataTables
    function initTable3(department_id) {
        //通讯录成员表格
        var oTable = $('#simple-table3')
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
                "sAjaxSource": '/home/car/getmemberlist/departmentId/'+ department_id +'/type/1',
                "sServerMethod": "GET",
                "oLanguage": {
                    "sZeroRecords": "没有找到符合条件的数据",
                    "sInfoEmpty": "没有记录",
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

        var treeObj3 = $("#department_tree3");
        treeObj3.addClass("showIcon");
        var zNodes3 = null;
        $.ajax({
            type: "GET",
            url: "/home/department/getdepartmenttree",
            dataType: "json",
            success: function (res) {
                zNodes3 = res;
                $.fn.zTree.init(treeObj3, setting3, zNodes3);
            }
        });

        //初始化Datatables
        //initTable3(1);

        treeObj3.hover(function () {
            if (!treeObj3.hasClass("showIcon")) {
                treeObj3.addClass("showIcon");
            }
        }, function() {
        });

        //点击配置人员按钮，获取要配置的流程节点id
        var id3 = null;
        var process_id3 = null;
        $("#dynamic-table").on('click', 'a.setexecute',  function(){
            //每次点击配置人员，都是删除上一次的选中状态，并刷新列表
            $("#department_tree3 .curSelectedNode").removeClass("curSelectedNode");
            initTable3(1);
            select_memb_arr = [];
            id3 = $(this).attr('data-manage-id');
            process_id3 = $(this).attr('data-id');
        });

        //设置管理员
        $('#member_submit3').click(function(){
            var member_id3="";
            //如果没有选中人员
            if(!!select_memb_arr && select_memb_arr.length == 0){
                bootbox.alert('请至少选择一个用户');
                return false;
            }else{
                //把已选中的人员数组变为字符串
                member_id3 = select_memb_arr.join(',');
                console.log(member_id3);return false;
            }

//            $('input:checkbox[name=member_id]:checked').each(function(i){
//                if(0==i){
//                    member_id3 = $(this).val();
//                }else{
//                    member_id3 += (","+$(this).val());
//                }
//            });
            //如果没有选中用户，提示错误
//            if( member_id3 == '' ){
//                bootbox.alert('请至少选择一个用户');
//                return false;
//            }
            //派发流程和办理流程 所设置的人员所属部门默认为0
            var depart_id3 = 0;

            $.ajax({
                type : "POST",
                url : "/home/car/setmanage/",
                data : {
                    id : id3,
                    member_id : member_id3,
                    depart_id : depart_id3,
                    process_id : process_id3
                },
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            //关闭当前弹出框，刷新当前页面
                            $("#set_execute_manage").css({display: "none","aria-hidden":"true"});
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

    //配置人员弹出层，选中人员数据设置，因为js控件只要涉及到分页，提交的时候只能提交当前选中的，所以设计为每次点击一个人员，如果已选中 就删除，反正添加。
    var select_memb_arr = [];
    function selectmeb_id(dom){
        //如果已经选中，就删除
        if(jQuery.inArray(dom.value,select_memb_arr) >= 0){
            select_memb_arr.splice(jQuery.inArray(dom.value,select_memb_arr),1)
        }else{
            //没有选中，添加
            select_memb_arr.push(dom.value);
        }
    }

</SCRIPT>