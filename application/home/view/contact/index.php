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

            <div class="page-header align-right">
                <a class="btn btn-primary" href="#department-add-modal" data-toggle="modal" id="add_department_modal">创建部门</a>
                <a class="btn btn-primary" href="#member-add-modal" data-toggle="modal" id="add_member_modal">创建用户</a>
                <button class="btn btn-primary" type="button" id="init_contact" data-loading-text="初始化中……">初始化通讯录</button>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <style type="text/css">
                    .ztree li span.button{  margin-left:5px !important; margin-top:-3px !important;  display: inline-block; text-align: center; background-position: initial !important; vertical-align: middle !important; font: normal normal normal 14px/1 FontAwesome; font-size: inherit; text-rendering: auto; -webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;}
                    .ztree li span.button.edit{ color: #478fca!important; background-image: initial !important; line-height: 15px;}
                    .ztree li span.button.remove{ color: #dd5a43!important; background-image: initial !important; line-height: 15px;}
                    .ztree li span.button.edit:before { content: "\f040"; }
                    .ztree li span.button.remove:before{ content: "\f00d";}
                    </style>
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
                                    <h4 class="widget-title lighter smaller">人员列表</h4>
                                </div>

                                <div class="widget-body">
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>姓名</th>
                                            <th>账号</th>
                                            <th>微信号</th>
                                            <th class="hidden-480">手机</th>
                                            <th class="hidden-480">邮箱</th>
                                            <th>状态</th>
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

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div>


<script src="/static/base/js/chosen.jquery.min.js"></script>
{include file="contact/department_modal" /}
{include file="contact/member_modal" /}
{include file="layout/tree" /}
{include file="layout/datatable" /}
<!-- inline scripts related to this page -->
<script type="text/javascript">
    var curMenu = null, zTree_Menu = null;
    var zTree;
    var rMenu = $(".rMenu");
    var setting = {
        view: {
            showLine: false,
            showIcon: false,
            selectedMulti: false,
            dblClickExpand: false,
            addDiyDom: addDiyDom,
            addHoverDom: addHoverDom,
		    removeHoverDom: removeHoverDom
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
            //beforeEditName: beforeEdit,
            //beforeRemove: beforeRemove
        }
    };

    function addDiyDom(treeId, treeNode) {
        var spaceWidth = 6;
        var switchObj = $("#" + treeNode.tId + "_switch"),
            icoObj = $("#" + treeNode.tId + "_ico");
        //switchObj.remove();
        icoObj.before(switchObj);

//        if(treeNode.id != 1){
//            console.log(treeNode.id);
//            var aObj = $("#" + treeNode.tId + "_a");
//            //var aObj = $("#" + treeNode.tId);
//            var editStr = '<span style="color: #000000;font-size: 14px;">[编辑]</span><span style="color: #000000;font-size: 14px;">[删除]</span>';
//            aObj.append(editStr);
//        }

        if (treeNode.level > 1) {
            var spaceStr = "<span style='display: inline-block;width:" + (spaceWidth * treeNode.level * 2)+ "px'></span>";
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
        //通讯录成员表格
        var oTable = $('#dynamic-table')
            .wrap("<div class='dataTables_borderWrap' />")
            .dataTable({
                "bAutoWidth": false,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": false,
                "bStateSave": true, //状态保存
                "bProcessing": true,
                "bDestroy":true,
                "bJQueryUI": false,
                "sPaginationType": "full_numbers",
                "bInfo": true,//页脚信息
                "sAjaxSource": '/home/member/getmemberlist/departmentid/' + department_id,
                "sServerMethod": "GET",
                "oLanguage": {
                    "sLengthMenu": "每页显示 _MENU_条",
                    "sZeroRecords": "没有找到符合条件的数据",
                    "sProcessing": "数据加载中……",
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

    function getMemberInfo(id){
        $.ajax({
            type : "GET",
            url : "/home/member/getmemberinfo/id/" + id,
            dataType: "json",
            success : function(res){ //res:{1:成功}
                if(res.result == 1){
                    $("#member-edit-modal").modal('show');
                    document.getElementById("member-edit-form").reset();
                    var info = res.extension;
                    $("#em_id").val(info.id);
                    $("#em_name").val(info.name);
                    $("#em_userid").val(info.userid);
                    $("#em_userid_display").val(info.userid);
                    if(info.gender == '1'){
                        $("input[data-name='em_gender'][value='1']").attr("checked",true);
                        $("input[data-name='em_gender'][value='2']").attr("checked",false);
                    }else{
                        $("input[data-name='em_gender'][value='2']").attr("checked",true);
                        $("input[data-name='em_gender'][value='1']").attr("checked",false);
                    }
                    $("#em_weixinid").val(info.weixinid);
                    $("#em_mobile").val(info.mobile);
                    $("#em_email").val(info.email);
                    var depart = [];
                    info.department.forEach(function(e){
                        depart.push(e);
                    });
                    $("#em_department_ids").chosen('destroy').val(depart).chosen({allow_single_deselect:true});
                    $(window)
                        .off('resize.chosen')
                        .on('resize.chosen', function() {
                            $('.chosen-select').each(function() {
                                var $this = $(this);
                                $this.next().css({'width': '100%'});
                            })
                        }).trigger('resize.chosen');
                }else{
                    bootbox.alert(res.message,function(){

                    });
                }
            }
        });
    }

    function deleteMember(id,userid){
        bootbox.confirm("确定要删除该成员吗？", function(result) {
            if(result){
                $.ajax({
                    type: "POST",
                    url: "/home/member/delete",
                    data: {id: id, userid: userid},
                    dataType: "json",
                    success: function (res) { //res:{1:成功}
                        if (res.result == 1) {
                            bootbox.alert(res.message, function () {
                                window.location.reload();
                            });
                        } else {
                            bootbox.alert(res.message, function () {

                            });
                        }
                    }
                });
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
                $.fn.zTree.init(treeObj, setting, zNodes);
                zTree =  $.fn.zTree.getZTreeObj("department_tree");
            }
        });

        //初始化Datatables
        initTable(1);

        $('#department_submit').click(function(){
            $.ajax({
                type : "POST",
                url : "/home/department/add",
                data : $('#department-add-form').serialize(),
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

        $("#add_department_modal").click(function(){
            $("#department_name").val("");
            $("#parent_id").val("");
        });

        $('#init_contact').on(ace.click_event, function () {
            var btn = $(this);
            btn.button('loading');
            $.ajax({
                type : "GET",
                url : "/home/contact/init",
                data : {},
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
                    btn.button('reset');
                }
            });
        });

    });

    //metro菜单 start
    var newCount = 1;
    function addHoverDom(treeId, treeNode) {
        if (treeNode.editNameFlag || $("#editBtn_"+treeNode.tId).length>0) return;
        var btn = $("#editBtn_"+treeNode.tId);
        if (btn) btn.bind("click", function(){
            var zTree = $.fn.zTree.getZTreeObj("department_tree");
            zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
            return false;
        });
    };
    function removeHoverDom(treeId, treeNode) {
        $("#editBtn_"+treeNode.tId).unbind().remove();
    };
    //metro菜单 end
</script>
<script type="text/javascript">
    jQuery(function($) {

        if(!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect:true});

            //resize the chosen on window resize
            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': '100%'});
                    })
                }).trigger('resize.chosen');

        }
    });
</script>
