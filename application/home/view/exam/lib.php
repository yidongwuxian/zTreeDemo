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
					<a href="#">题库列表</a>
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
					<h4>题库列表 
						<a class="import" href="#modal-import" data-toggle="modal" role="button">导入题目</a>
						<a class="addBtn" href="#modal-add" data-toggle="modal" role="button">添加题库</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="7%" class="center">题库ID</th>
								<th width="20%">题库名称</th>
								<th width="25%">题库简介</th>
								<th width="10%"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>创建时间</th>
								<th width="10%">状态</th>
								<th width="20%" class="sorting_disabled">操作</th>
							</tr>
						</thead>

						<tbody>
						<?php foreach ($libList as $vo):?>
							<tr>
								<td class="center"><?php echo $vo['id'];?></td>	
								<td><?php echo $vo['title'];?></td>	
								<td><?php echo $vo['description'];?></td>
								<td><?php echo date('Y-m-d H:i:s', $vo['create_time']);?></td>
								<td>
									<?php echo $vo['status']==1? '正常' : ($vo['status']==2? '停用' : '废弃') ;?>
								</td>
								<td>
									<div class="btn-group">
										<a class="btn btn-xs btn-info" href="/home/exam/problem/id/<?php echo $vo['id'];?>" title="查看题目">
											<i class="ace-icon fa fa-list"></i>
											查看题目
										</a>
										<a class="btn btn-xs btn-success" href="/home/exam/addproblem/id/<?php echo $vo['id'];?>" title="添加问题">
											<i class="ace-icon fa fa-plus"></i>
											添加题目
										</a>
										<a class="btn btn-xs btn-info editBtn" href="#modal-add" data-toggle="modal" role="button" title="编辑">
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

		<!-- 新增题库层效果 start-->
		<div id="modal-add" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="widget-header">
							<h4 class="widget-title">添加题库</h4>
							<div class="widget-toolbar">
								<a aria-hidden="true" data-dismiss="modal" class="close" type="button" style="font-size: 16px;line-height: 36px;">
									<i class="ace-icon fa fa-times"></i>
								</a>

							</div>
						</div>
					</div>
					<form id="modal-add-form" method="post" action="" role="form" class="form-horizontal" novalidate="novalidate">
						<div class="modal-body">							
							<input type="hidden" name="id" id="id" value="">
							<div class="form-group">
								<label for="type" class="col-sm-3 control-label no-padding-right">题库名称</label>
								<div class="col-sm-9">
									<div class="clearfix">
										<input class="form-controls col-sm-10" type="text" name="title" placeholder="题库名称">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="startDate" class="col-sm-3 control-label no-padding-right">题库简介</label>
								<div class="col-sm-9">
									<textarea class="form-controls" name="description" placeholder="题库简介" cols="38" rows="3"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="content" class="col-sm-3 control-label no-padding-right">状态</label>
								<div class="col-sm-9">
									<select name="status" class="form-controls col-sm-10">
										<option value="1" selected="selected">正常</option>
										<option value="2">停用</option>
										<option value="3">废弃</option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer no-margin-top">
							<button data-dismiss="modal" class="btn btn-sm">
								<i class="ace-icon fa fa-times"></i>
								取消
							</button>

							<button class="btn btn-sm btn-primary" id="submit" type="submit">
								<i class="ace-icon fa fa-check"></i>
								保存
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- 新增题库层效果 end -->
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
	<!-- 						<div class="form-group">
								<label for="libId" class="col-sm-3 control-label no-padding-right">题库</label>
								<div class="col-sm-9">
									<div class="clearfix">
										<select id="libId" name="libId" class="form-controls col-sm-10">
										<?php foreach ($libList as $vo):?>
											<option value="<?php echo $vo['id'] ?>"><?php echo $vo['title'] ?></option>
										<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div> -->
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
<div id="target"></div>

{include file="layout/datatable" /}
<script src="/static/base/js/jquery.validate.min.js"></script>

<script type="text/javascript">
jQuery(function($){

	// datatable init
	var oTable1 = $('#dynamic-table')
	.wrap("<div class='dataTables_borderWrap' />")
	.dataTable({
		"aoColumns": [
		  null, null, null, null, null, null
		],			
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
			"sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
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

	// 添加题库
	$(".table-header .addBtn").on('click', function(){
		var con = $("#modal-add");
		con.find("form")[0].reset();
		con.find('input[name=id]').val('');
		con.find('.modal-header h4').html('添加题库');
	});
	// 获取题库详情
	$("#dynamic-table .editBtn").on('click', function(){
		var id  = $(this).siblings('input').val();
		var subcon = $("#modal-add form");
		$('#modal-add .modal-header h4').html('编辑题库');
		if(isNaN(id)){
			$('#modal-add').modal('hide');
			bootbox.alert("请求参数错误");
			return false;
		}
		$.ajax({
			url:'/home/exam/getlibinfo/id/' + id,
			type:'get',
			dataType:'json',
			success:function(res){
				if (res.result == 1){
					subcon.find("input[name=id]").val(res.extension.id);
					subcon.find("input[name=title]").val(res.extension.title);
					subcon.find("textarea[name=description]").val(res.extension.description);
					subcon.find("select[name=status]").val(res.extension.status);
		        } else {
		        	bootbox.alert(res.message);
		        	$('#modal-add').modal('hide');
		        }
		        return false;
			},
			error: function() {
				bootbox.alert('系统错误');
				return false;
			},
		});
	});

	// 删除题库
	$("#dynamic-table .btn-delete").on('click', function(){
		var id  = $(this).siblings('input').val();
		bootbox.confirm('您确定要删除吗？', function(result){
			if(result){
				$.ajax({
					url:'/home/exam/deletelib/id/' + id,
					type:'get',
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

	// 保存方法
	function saveLib(){
		var data = {};
		data.id 	= $.trim($('#modal-add input[name="id"]').val());
		data.title 	= $.trim($('#modal-add input[name="title"]').val());
		data.status	= $('#modal-add select[name="status"]').val();
		data.description = $.trim($('#modal-add textarea[name="description"]').val());

		$.ajax({
			type: "post",
			url: '/home/exam/savelib/',
			data: data,
			dataType: "json",
			success: function(res) {
				if (res.result == 1){
					bootbox.alert('操作成功', function(){
						window.location.reload();
					});
					return false;
				} else {
					bootbox.alert(res.message);
					return false;
				}
			}
		});
	}

	// form validate
	$('#modal-add-form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: true,
		rules: {
			title: {
				required: true,
				minlength: 2,
				maxlength:100
			},
		},
		messages: {
			title: {
				required: "题库名称不能为空",
				minlength: "动动手指头再加俩字吧",
				maxlength: "囧 标题需要精简"
			},
		},
		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},
		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');
			$(e).remove();
		},
		errorPlacement: function (error, element) {
			if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
			else error.insertAfter(element.parent());
		},
		submitHandler: function (form) {
			saveLib();
		},
		invalidHandler: function (form) {
		}
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

})


</script>

