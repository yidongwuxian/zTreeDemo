
/*
 * 选择人员，需要ztree，modal模态框支持
 */
;(function($, window, undefined){
    var defaults = {
            chooseType: 'single'    //选择模式，单选single /多选 multi
        },
        chooseType;//缓存记录当前选择模式


    var createModal = function(){
        var modalHtml = '<div id="set_choose_manage" class="modal fade" tabindex="-1">'+
'           <div class="modal-dialog" style="width: 720px">'+
'               <div class="modal-content">'+
'                   <div class="modal-header no-padding">'+
'                       <div class="table-header">'+
'                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">'+
'                               <span class="white">&times;</span>'+
'                           </button>创建成员   '+
'                       </div>'+
'                   </div>'+
'                   <div class="row">'+
'                       <div class="col-xs-12" style="padding-left: 17px;padding-right: 17px;">'+
'                           <div class="row">'+
'                               <div class="col-sm-3" style="width: 35%;padding-right: 0px;">'+
'                                   <div class="widget-box widget-color-blue2">'+
'                                       <div class="widget-header">'+
'                                           <h4 class="widget-title lighter smaller">部门</h4>'+
'                                       </div>'+
'                                       <div class="widget-body">'+
'                                           <div class="widget-main padding-8">'+
'                                               <ul id="department_tree_modal" class="ztree"></ul>'+
'                                           </div>'+
'                                       </div>'+
'                                   </div>'+
'                               </div>'+
'                               <div class="col-sm-9" style="width: 65%;padding-left: 5px;">'+
'                                   <div class="widget-box widget-color-blue2">'+
'                                       <div class="widget-header">'+
'                                           <h4 class="widget-title lighter smaller">人员列表</h4>'+
'                                       </div>'+
'                                       <div class="widget-body">'+
'                                           <table id="simple-table" class="table table-striped table-bordered table-hover">'+
'                                               <thead>'+
'                                               <tr><th>姓名</th><th>操作</th></tr>'+
'                                               </thead>'+
'                                               <tbody>'+
'                                               </tbody>'+
'                                           </table>'+
'                                           <input type="hidden" id="depart_id" value="">'+
'                                       </div>'+
'                                   </div>'+
'                               </div>'+
'                           </div>'+
'                       </div>'+
'                   </div>'+
'                   <div class="modal-footer no-margin-top align-right">'+
'                       <a class="btn btn-sm btn-primary" id="member_submit">'+
'                           <i class="ace-icon fa fa-check"></i>确定'+
'                       </a>'+
'                       <a class="btn btn-sm btn-danger" data-dismiss="modal">'+
'                           <i class="ace-icon fa fa-times"></i>取消'+
'                       </a>'+
'                   </div>'+
'               </div>'+
'           </div>'+
'       </div>';
        return $(modalHtml).appendTo($('body'));
    };

    //配置ztree
    var curMenu = null, zTree_Menu = null;
    var settingModal = {
        view: {
            showLine: false,
            showIcon: false,
            selectedMulti: false,
            dblClickExpand: false,
            addDiyDom: addDiyDomModal
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
            beforeClick: beforeClickModal
        }
    };


    function addDiyDomModal(treeId, treeNode) {
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

    function beforeClickModal(treeId, treeNode) {
        //动态加载数据
        var currentDepartId = treeNode.department_id;
        initTableModal(currentDepartId);
    }

    //初始化DataTables
    function initTableModal(department_id) {
        //通讯录成员表格
        var oTable = $('#simple-table')
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
                "sProcessing": "正在加载数据...",
                "bInfo": false,//页脚信息
                "sAjaxSource": '/home/member/getmemberlistformodal/departmentid/' + department_id + (chooseType=='multi'? '/type/1' :''),
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
        return oTable;
    }

    function selectmeb_id($dom){
        //如果已经选中，就删除
        if($dom.prop('checked')==true){
            this.selectedValues.push({val: $dom.val(), name: $.trim($dom.parents('td').prev().html())});
        }else{
            for(var i = 0, k = this.selectedValues.length; i < k; i++){
                if($dom.val() == this.selectedValues[i].val){
                    this.selectedValues.splice(i, 1);break;
                    //f = 1;
                }
            }
        }
        /*var f = 0;
        for(var i = 0, k = this.selectedValues.length; i < k; i++){
            if($dom.val() == this.selectedValues[i].val){
                this.selectedValues.splice(i, 1);
                f = 1;
            }
        }
        if(!f){
            this.selectedValues.push({val: $dom.val(), name: $.trim($dom.parents('td').prev().html())});
        }*/
       /* if(jQuery.inArray($dom.val(),this.selectedValues) >= 0){
            this.selectedValues.splice(jQuery.inArray($dom.val(),this.selectedValues),1)
        }else{*/
            //没有选中，添加
            //this.selectedValues.push($dom.val());
        //}
    }

    //插件相关
    
    function choosePerson(element, options){
        this.element = $(element);
        this.opts = $.extend({}, defaults, options);
        this.$modal = $('#set_choose_manage');//模态框
        this.table = null;//右侧人员列表datatable
        this.selectedValues = null;//最终选中value或者集合，根据是单选还是多选
        chooseType = this.opts.chooseType;
        this.init();      
    }

    choosePerson.prototype.init = function(){
        var that = this;
        $(that.element).on('click',function(){
            //是否已经创建，未创建会创建html
            if(!that.$modal.length){
                chooseType = that.opts.chooseType;
                that.$modal = createModal();
                that.loadModalContent();
                that.table = initTableModal(1);//第一次加载表格
            }else{
                that.reloadContent();
            }
            that.$modal.modal('show');
        });
        setTimeout(function(){
            //多选情况记录多选集合
            if(chooseType == 'single'){
                that.selectedValues = null;
            }else{
                that.selectedValues = [];
                $('body').delegate('input:checkbox[name="member_id"]','change',function(){
                    selectmeb_id.call(that, $(this));
                });
            }
            $('body').delegate('#member_submit', 'click', function(){
                if(chooseType == 'single'){
                    if(typeof that.opts.onsubmit == 'function'){
                        var $selected = $("input[name='member_id']:checked");
                        that.opts.onsubmit({val: $selected.val(), name: $.trim($selected.parents('td').prev().html())},chooseType);
                    }
                }else{
                    if(typeof that.opts.onsubmit == 'function')
                        that.opts.onsubmit(that.selectedValues,chooseType);
                }
                that.$modal.modal('hide');
            });
        },0);
    };
    choosePerson.prototype.loadModalContent = function(){
        var treeObj = $("#department_tree_modal").addClass("showIcon");
        var zNodes = null;
        $.ajax({
            type: "GET",
            url: "/home/department/getdepartmenttree",
            dataType: "json",
            success: function (res) { //res:{1:成功}
                zNodes = res;
                $.fn.zTree.init(treeObj, settingModal, zNodes);
            }
        });
        treeObj.hover(function () {
            if (!treeObj.hasClass("showIcon")) {
                treeObj.addClass("showIcon");
            }
        }, function() {
        });
    };
    choosePerson.prototype.reloadContent = function(){
        //将选中状态的节点取消
        var treeObj = $.fn.zTree.getZTreeObj("department_tree_modal");
        treeObj.cancelSelectedNode();
        this.selectedValues = [];
        //将勾选的取消
        //重绘
        this.table._fnAjaxUpdate();
        this.table._fnPageChange(0);
    }


    window.choosePerson = choosePerson;
})(jQuery, window, undefined);