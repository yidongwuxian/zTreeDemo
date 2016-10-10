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
                    <a href="#">通讯录</a>
                </li>
                <li class="active">部门</li>
            </ul><!-- /.breadcrumb -->

        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-sm-3">
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

                        <div class="col-sm-9">
                            <div class="widget-box widget-color-blue2">
                                <div class="widget-header">
                                    <h4 class="widget-title lighter smaller">审核流程列表</h4>
                                </div>

                                <div class="widget-body">
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="20%">流程顺序</th>
                                            <th width="40%">流程节点名称</th>
                                            <th width="20%">配置人员</th>
                                            <th width="20%">操作</th>
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
                        var $assets = "dist";//this will be used in fuelux.tree-sampledata.js
                    </script>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div>

{include file="car/set_manage" /}
{include file="layout/tree" /}
{include file="layout/datatable" /}

<!-- inline scripts related to this page -->
<SCRIPT type="text/javascript">
    <!--
    var curMenu = null, zTree_Menu = null;
    var setting = {
        view: {
            showLine: false,
            showIcon: false,
            selectedMulti: false,
            dblClickExpand: false,
            addDiyDom: addDiyDom
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
            beforeClick: beforeClick
            //beforeEditName: beforeEdit
            //beforeRemove: beforeRemove
        }
    };

    function addDiyDom(treeId, treeNode) {
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

    function beforeClick(treeId, treeNode) {
        //动态加载数据
        var currentDepartId = treeNode.department_id;
        initTable(currentDepartId);
    }

    //初始化DataTables
    function initTable(department_id) {
        //给添加审核级别按钮赋值 部门id
        $('#depart_id').val(department_id);
        //通讯录成员表格
        var oTable = $('#dynamic-table')
            .wrap("<div class='dataTables_borderWrap' />")
            .dataTable({
                "paging": false,
                "bAutoWidth": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bStateSave": false, //状态保存
                "bProcessing": true,
                "bDestroy":true,
                "bJQueryUI": false,
                "sPaginationType": "full_numbers",
                "bInfo": false,//页脚信息
                "sAjaxSource": '/home/car/getdepartmanagelist/departmentid/' + department_id,
                "sServerMethod": "GET",
                "oLanguage": {
                    "sZeroRecords": "没有找到符合条件的数据",
                    "sInfoEmpty": "没有记录"
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
            success: function (res) {
                zNodes = res;
                $.fn.zTree.init(treeObj, setting, zNodes);
            }
        });

        //初始化Datatables
        initTable(1);

        treeObj.hover(function () {
            if (!treeObj.hasClass("showIcon")) {
                treeObj.addClass("showIcon");
            }
        }, function() {
            //treeObj.removeClass("showIcon");
        });

    });


</SCRIPT>