<div id="department-add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    创建部门
                </div>
            </div>

            <div class="modal-body padding-14">
                <form class="form-horizontal" role="form" id="department-add-form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="corp_name"> 部门名称 </label>
                        <div class="col-sm-10">
                            <input type="text" id="department_name" name="department_name" placeholder="部门名称" class="col-xs-10 col-sm-10" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="corp_name"> 父级部门 </label>
                        <div class="col-sm-9">
                        <select class="chosen-select form-control" id="parent_id" name="parent_id" data-placeholder="请选择所属父级部门">
                            <option value="">请选择</option>
                            <?php echo $departmentSelectStr; ?>
                        </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer no-margin-top align-right">
                <a class="btn btn-sm btn-primary" id="department_submit">
                    <i class="ace-icon fa fa-check"></i>
                    添加
                </a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    取消
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- PAGE CONTENT ENDS -->