<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">首页</a>
				</li>
		
				<li>
					<a href="#">应用中心</a>
				</li>
				
				<li>
					<a href="/home/exam/index">考试</a>
				</li>
				<li>
					<a href="#">题目列表</a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>
		<style type="text/css">
			.table-header h4 a {margin-right:1%;float: right;color: #fff;}
		</style>
		<div class="page-content row">
			<div class="col-xs-12 widget-box widget-color-blue2">
				<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
				<div class="table-header widget-header">
					<h4>题目列表
						<a class="import" href="#modal-import" data-toggle="modal" role="button">导入题目</a>
						<a href="/home/exam/lib" role="button">返回题库列表</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="center sorting_disabled" width="5%">
									<label class="pos-rel">
										<input type="checkbox" class="ace" name="all">
										<span class="lbl"> </span>
									</label>
								</th>
								<th class="hidden-480" width="40%">问题名称</th>
								<th class="hidden-480" width="5%">所属题库</th>
								<th width="5%">类型</th>
								<th width="5%">分数</th>
								<th width="10%"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>创建时间</th>
								<th width="10%" class="sorting_disabled">操作</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($problemList as $vo):?>
							<tr>
								<td class="center">
									<label class="pos-rel">
										<input type="checkbox" class="ace" name="delpro" value="<?php echo $vo['id'];?>">
										<span class="lbl"></span>
									</label>
								</td>
								<td><?php echo $vo['title'];?></td>
								<td><?php echo isset($libList[$vo['lib_id']]) ? $libList[$vo['lib_id']] : '题库出错';?></td>	
								<td><?php echo $vo['type'] == 1 ? '单选' : '多选';?></td>
								<td><?php echo $vo['score'];?></td>
								<td><?php echo date('Y-m-d H:i:s', $vo['create_time']);?></td>
								<td>
									<div class="btn-group">
										<a class="btn btn-xs btn-info editBtn"  href="/home/exam/getProblemInfo/id/<?php echo $vo['id'];?>" role="button" title="编辑">
											<i class="ace-icon fa fa-edit"></i>
											编辑
										</a>
										<a href="javascript:;" class="btn btn-xs btn-danger btn-delete" title="删除">
											<i class="ace-icon fa fa-trash-o"></i>
											删除
										</a>
										<input type="hidden" value="<?php echo $vo['id'];?>">
									</div>									
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- 变更题库 start -->
		<div id="modal-update" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="widget-header">
							<h4 class="widget-title">变更题库</h4>
							<div class="widget-toolbar">
								<a aria-hidden="true" data-dismiss="modal" class="close" type="button" style="font-size: 16px;line-height: 36px;">
									<i class="ace-icon fa fa-times"></i>
								</a>

							</div>
						</div>
					</div>
					<form role="form" class="form-horizontal" >
						<div class="modal-body" style="margin-bottom: 10px;margin-top: 5px;">							
							<input type="hidden" name="id" id="id" value="">
							<div class="form-group">
								<label for="libId" class="col-sm-3 control-label no-padding-right">题库</label>
								<div class="col-sm-9">
									<div class="clearfix">
										<select id="libId" name="libId" class="form-controls col-sm-10">
										<?php foreach ($libList as $key => $vo):?>
											<option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
										<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" id="ids" name="ids"/>
						<div class="modal-footer no-margin-top">
							<button data-dismiss="modal" class="btn btn-sm">
								<i class="ace-icon fa fa-times"></i>
								取消
							</button>

							<a class="btn btn-sm btn-primary" id="update">
								<i class="ace-icon fa fa-check"></i>
								保存
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- 变更题库 end -->
		<!-- 导入题目层效果 start -->
		<div id="modal-import" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="widget-header">
							<h4 class="widget-title">批量导入题目</h4>
							<div class="widget-toolbar">
								<a aria-hidden="true" data-dismiss="modal" class="close" type="button" style="font-size: 16px;line-height: 36px;">
									<i class="ace-icon fa fa-times"></i>
								</a>

							</div>
						</div>
					</div>
					<form id="modal-import-form" action="/home/exam/import/" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
						<div class="modal-body">
							<style type="text/css">#file + span{width: 350px !important;} .ace-file-multiple .remove{right: 60px !important;}</style>
							<div class="form-group">
								<label for="file" class="col-sm-3 control-label no-padding-right">上传EXCEL</label>
								<div class="col-sm-9">
									<input type="file" id="file" name="file" class="form-controls col-sm-10"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right">下载模板</label>
								<div class="col-sm-9">
									<span style="height:32px;line-height: 32px;font-size: 14px;">
										<a target="_blank" href="/static/demo/libImportDemo.xlsx" class="form-controls col-sm-10">EXCEL模板下载</a>
									</span>
								</div>
							</div>
						</div>
						<div class="modal-footer no-margin-top">
							<button data-dismiss="modal" class="btn btn-sm">
								<i class="ace-icon fa fa-times"></i>
								取消
							</button>

							<a class="btn btn-sm btn-primary" id="import">
								<i class="ace-icon fa fa-check"></i>
								保存
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- 导入题目层效果 end -->
	</div>
</div>
{include file="layout/datatable" /}
<script type="text/javascript">
jQuery(function($){

	// datatable init
	var oTable1 = $('#dynamic-table')
	.wrap("<div class='dataTables_borderWrap' />")
	.dataTable({
		"aoColumns": [
			{ "bSortable": false},
			null, null,null, null, null,
			{ "bSortable": false }
		],
		// "aaSorting": [],			
		"bAutoWidth": false,
		"bLengthChange": true,
		"bFilter": true,
		"bSort": true,
		"bStateSave": true, //状态保存
		"bDestroy":true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bInfo": true,//页脚信息
		"oLanguage": {
			"sLengthMenu": "每页显示 _MENU_条",
			"sZeroRecords": "没有找到符合条件的数据",
			"sProcessing": "&lt;img src='/public/static/base/img/loading.gif' /&gt;",
			"sInfo": '<button class="btn btn-xs" id="delbatch"><i class="menu-icon fa fa-trash-o bigger-120"></i>批量删除</button> &nbsp; <button class="btn btn-xs" id="movebatch"><i class="menu-icon fa fa-share bigger-120"></i>变更题库</button> &nbsp; ' +"当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
			"sInfoEmpty": "木有记录",
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
	var tableTools_obj = new $.fn.dataTable.TableTools( oTable1, {
		"sRowSelector": "td:not(:last-child)",
		"sRowSelect": "multi",
		"fnRowSelected": function(row) {
			//check checkbox when row is selected
			try { $(row).find('input[type=checkbox]').get(0).checked = true }
			catch(e) {}
		},
		"fnRowDeselected": function(row) {
			//uncheck checkbox
			try { $(row).find('input[type=checkbox]').get(0).checked = false }
			catch(e) {}
		},

		"sSelectedClass": "success",
	} );
	// $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));
    // $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
	$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
		var th_checked = this.checked;//checkbox inside "TH" table header
		
		$(this).closest('table').find('tbody > tr').each(function(){
			var row = this;
			if(th_checked) tableTools_obj.fnSelect(row);
			else tableTools_obj.fnDeselect(row);
		});
	});
	$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
		var row = $(this).closest('tr').get(0);
		if(!this.checked) tableTools_obj.fnSelect(row);
		else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
	});

	// 删除
	$("#dynamic-table").on('click', '.btn-delete', function(){
		var id = $(this).siblings('input').val();
		var con = $(this);
		if (id == '')
		{
			bootbox.alert("请求参数错误");
			return false;
		}

		bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function(result){
			if (result)
			{
				$.ajax({
					type: "get",
					url: '/home/exam/deleteproblem/id/' + id,
					dataType: "json",
					success: function(res) {
						if (res.result == 1){
							bootbox.alert("操作成功", function (result) {  
				               con.parents("tr").remove(); 
				            }); 
							return false;
						} else {
							bootbox.alert(res.message);
							return false;
						}
					}
				});
			}
		});

	});

	// 批量删除
	$("#dynamic-table_wrapper").on('click', '#delbatch', function(){
		var delIds = [];
		$('#dynamic-table input[name="delpro"]:checked').each(function(){
			delIds.push($(this).val());
		}); 

		if (delIds.length == 0)
		{
			bootbox.alert('请勾选您要删除的记录');
			return false;
		}

		bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function (result) {  
            if(result) {  
				$.ajax({
					url:'/home/exam/deleteproblembatch/',
					type:'post',
					data:{delIds:delIds.join("|")},
					dataType:'json',
					success:function(res){
						if (res.result == 1){
							bootbox.alert(res.message, function(){
								window.location.reload();
							});					
						} else {
							bootbox.alert(res.message);
						}
						return false;
					},
					error:function(){
						bootbox.alert('系统错误');
						return false;
					}
				});              
            } 
        });
	});

	// 变更题库
	$("#dynamic-table_wrapper").on('click', '#movebatch', function(){
		var delIds = [];
		$('#dynamic-table input[name=delpro]:checked').each(function(){
			delIds.push($(this).val());
		}); 
		if (delIds.length == 0)
		{
			bootbox.alert('请勾选您要变更的记录');
			return false;
		}
		$("#modal-update input[name='ids']").val(delIds.join('~'));

		$("#modal-update").modal('show');
	});
	// 变更提交
	$("#modal-update").on('click', '#update', function(){
		var con   = $("#modal-update");
		var libId = con.find('select[name=libId]').val();
		var ids   = con.find('input[name=ids]').val();

		if (ids == '' || libId == '') {
			bootbox.alert('请求出错，请重试');
			window.location.reload();
		}
		$.ajax({
			url:'/home/exam/problemmovebatch/',
			type:'post',
			data:{libId:libId, ids:ids},
			dataType:'json',
			success:function(res){
				if (res.result == 1){
					bootbox.alert(res.message, function(){
						con.modal('hide');
						window.location.reload();
					});					
				} else {
					bootbox.alert(res.message);
				}
				return false;
			},
			error:function(){
				bootbox.alert('系统错误');
				return false;
			}
		}); 
	});


	//file upload init 
	$('#file').ace_file_input({
		style:'well',
		btn_choose:'将文件拖放此处 或者 点击选择上传',
		btn_change:null,
		no_icon:'ace-icon fa fa-cloud-upload',
		droppable:true,
		thumbnail:'small',
		preview_error : function(filename, error_code) { }
	}).on('change', function(){});

	var btn_choose  = '将文件拖放此处 或者 点击选择上传';
	var no_icon	 	= 'ace-icon fa fa-cloud-upload';
	var whitelist_ext  = <?php echo $ext; ?>;
	var whitelist_mime = <?php echo $mime; ?>;
	var file_input = $('#file');
	file_input.ace_file_input('update_settings',
	{
		'btn_choose': btn_choose,
		'no_icon': no_icon,
		'allowExt': whitelist_ext,
		'allowMime': whitelist_mime
	})
	file_input.ace_file_input('reset_input');
	
	file_input.off('file.error.ace').on('file.error.ace', function(e, info) {
		console.log(info.file_count);
		console.log(info.invalid_count);
		console.log(info.error_list);
		//info.error_count['ext']
		//info.error_count['mime']
		//info.error_count['size']
	});
	
	// 保存导入数据
	$("#modal-import").on('click', '#import', function(){
		var con = $("#modal-import");
		var fileId = 'file';
		var libId = con.find("select[name=libId]").val();
		if (con.find('input[name=file]').val() == ''){
			bootbox.alert("请选择文件");
			return false;
		}
		con.find('form').submit();
	});

});
</script>