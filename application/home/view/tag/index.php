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
                <a class="btn btn-primary" href="#tag-add-modal" data-toggle="modal">创建标签</a>
                <a class="btn btn-primary" href="#" data-toggle="modal" id="add_member">为标签添加个人成员</a>
                <a class="btn btn-primary" href="#department-add-modal" data-toggle="modal" id="add_department">为标签添加部门</a>
                <a class="btn btn-danger" href="#" id="delete_member">删除所选成员</a>
                <button class="btn btn-primary" type="button" id="init_tag" data-loading-text="初始化中……">初始化通讯录</button>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="widget-box widget-color-blue2">
                                <div class="widget-header">
                                    <h4 class="widget-title lighter smaller">标签</h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-8">
                                        <ul id="tag_tree" class="ztree"></ul>
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
                                            <th class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace" />
                                                    <span class="lbl"></span>
                                                </label>
                                            </th>
                                            <th>名称</th>
                                            <th>类别</th>
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
<div id="tag-add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    创建标签
                </div>
            </div>

            <div class="modal-body padding-14">
                <form class="form-horizontal" role="form" id="tag-add-form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="tag_name"> 标签名称 </label>
                        <div class="col-sm-10">
                            <input type="text" id="tag_name" name="tag_name" placeholder="标签名称不能超过32个字" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="tag_submit">
                    <i class="ace-icon fa fa-check"></i>
                    添加
                </a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    取消
                </a>
            </div>
        </div>
    </div>
</div>
<div id="department-add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    添加部门
                </div>
            </div>

            <div class="modal-body padding-14">
                <form class="form-horizontal" role="form" id="department-add-form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="department_ids"> 所属部门 </label>
                        <div class="col-sm-8">
                            <select multiple="" class="chosen-select form-control tag-input-style" id="department_ids">
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="department_submit">
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
<script src="/static/base/js/chosen.jquery.min.js"></script>
{include file="layout/tree" /}
{include file="layout/datatable" /}
<script src="/static/base/js/choosePerson.js"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    var curMenu = null, zTree_Menu = null;
    var currentTagId = null;
    var selectedValue = [];
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
            beforeClick: beforeClick,
            //beforeEditName: beforeEdit,
            //beforeRemove: beforeRemove
        }
    };

    function addDiyDom(treeId, treeNode) {
        var spaceWidth = 5;
        var switchObj = $("#" + treeNode.tId + "_switch"),
            icoObj = $("#" + treeNode.tId + "_ico");
        switchObj.remove();
        icoObj.before(switchObj);

        //将标签的class的level设置为1,0为大粗体字
        var aObj = $("#" + treeNode.tId + "_a");
        aObj.removeClass().addClass('level 1');
    }

    function beforeClick(treeId, treeNode) {
        //动态加载数据
        currentTagId = treeNode.tagid;
        initTable('dynamic-table','/home/tag/gettagmember/tagid/'+currentTagId);
        //选中数组清空
        selectedValue = [];
    }

    $(document).ready(function(){
        var treeObj = $("#tag_tree");
        treeObj.addClass("showIcon");
        var zNodes = null;
        $.ajax({
            type: "GET",
            url: "/home/tag/gettaglist",
            dataType: "json",
            success: function (res) { //res:{1:成功}
                zNodes = res;
                $.fn.zTree.init(treeObj, setting, zNodes);
            }
        });

    });


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

        var active_class = 'active';
        $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
            var th_checked = this.checked;//checkbox inside "TH" table header

            $(this).closest('table').find('tbody > tr').each(function(){
                var row = this;
                if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
            });
        });

        //select/deselect a row when the checkbox is checked/unchecked
        $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
            var $row = $(this).closest('tr');
            if(this.checked) $row.addClass(active_class);
            else $row.removeClass(active_class);
        });

        //创建标签
        $('#tag_submit').click(function(){
            var data = {};
            data.tagname = $('#tag_name').val();
            $.ajax({
                type: "POST",
                url: "/home/tag/add",
                data: data,
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
        });

        //添加个人成员
        var _execute = new choosePerson('#add_member',{
            chooseType: 'multi',
            onsubmit: function(values,type){
                if( ! currentTagId){
                    bootbox.alert('请先选中标签！');
                }
                var userid = [];
                for(var key in values){
                    //console.log(values[key]);
                    userid.push(values[key].val);
                }
                if(userid.length > 0){
                    $.ajax({
                        type: "POST",
                        url: "/home/tag/addmember",
                        data: {tagid:currentTagId,userid:userid},
                        dataType: "json",
                        success: function (res) { //res:{1:成功}
                            if (res.result == 1) {
                                bootbox.alert(res.message, function () {
                                    //window.location.reload();
                                    initTable('dynamic-table','/home/tag/gettagmember/tagid/'+currentTagId);
                                });
                            } else {
                                bootbox.alert(res.message);
                            }
                        }
                    });
                }else{
                    return false;
                }
            }
        });

        //点击添加部门到标签
        $('#add_department').click(function(){
            //加载部门列表
            setDepartmentList('department_ids');
        });

        //确定添加部门
        $('#department_submit').click(function(){
            if( ! currentTagId){
                bootbox.alert('请先选中标签！');
            }
            var ids = $('#department_ids').val();
            if(ids != null && ids.length > 0){
                $.ajax({
                    type: "POST",
                    url: "/home/tag/adddepartment",
                    data: {tagid:currentTagId,partyid:ids},
                    dataType: "json",
                    success: function (res) { //res:{1:成功}
                        if (res.result == 1) {
                            bootbox.alert(res.message, function () {
                                //window.location.reload();
                                initTable('dynamic-table','/home/tag/gettagmember/tagid/'+currentTagId);
                                //关闭添加部门模态框
                                $('#department-add-modal').modal('hide');
                            });
                        } else {
                            bootbox.alert(res.message);
                        }
                    }
                });
            }else{
                bootbox.alert('请至少选择一个部门！');
            }
        });

        //选择成员checkbox事件
        $('body').delegate('input:checkbox[data-type="check_member"]','change',function(){
             if(jQuery.inArray($(this).data('id'),selectedValue) >= 0){
                selectedValue.splice(jQuery.inArray($(this).data('id'),selectedValue),1)
             }else{
                //没有选中，添加
                selectedValue.push($(this).data('id'));
             }
        });

        //删除所选成员
        $('#delete_member').click(function(){
            if(selectedValue.length > 0){
                bootbox.confirm("确定要删除所选成员吗？", function(result) {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/home/tag/deletemember",
                            data: {memberid:selectedValue},
                            dataType: "json",
                            success: function (res) { //res:{1:成功}
                                if (res.result == 1) {
                                    bootbox.alert(res.message, function () {
                                        //window.location.reload();
                                        initTable('dynamic-table','/home/tag/gettagmember/tagid/'+currentTagId);
                                    });
                                } else {
                                    bootbox.alert(res.message);
                                }
                            }
                        });
                    }
                });
            }else{
                bootbox.alert('请至少选择一个成员！');
            }
        });

        //初始化标签
        $('#init_tag').on(ace.click_event, function () {
            var btn = $(this);
            btn.button('loading');
            $.ajax({
                type : "GET",
                url : "/home/tag/init",
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
</script>