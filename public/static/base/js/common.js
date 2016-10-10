/**
 * Created by Administrator on 2016/9/21.
 */
//初始化DataTables
function initTable(domid,url) {
    //通讯录成员表格
    var dom = '#' + domid.toString();
    var oTable = $(dom)
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
            "sAjaxSource": url,
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

//添加部门列表进入select
function setDepartmentList(domid){
    $.ajax({
        type: "GET",
        url: "/home/department/getdepartmentlistforselect",
        dataType: "json",
        success: function (res) { //res:{1:成功}
            var list = res.list;
            var dom = '#' + domid.toString();
            $(dom).chosen('destroy').val("").append(list).chosen({allow_single_deselect:true});
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
}
