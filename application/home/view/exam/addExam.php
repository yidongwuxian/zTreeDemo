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
					<a href="#"><?php echo isset($info['id']) ? '编辑试卷' : '新增试卷'; ?></a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>
		<style type="text/css">
			ul#optionsWrap li.col-sm-10 {list-style: none;margin-bottom: 15px;padding-left:0; }ul#optionsWrap li input{margin-right: 5%;}
			.col-sm-10 .chosen-container{width: 42% !important;}
			.col-sm-10 ul.chosen-choices{border: 1px solid #d5d5d5;border-radius: 0 !important;box-shadow: none !important;color: #858585;font-family: inherit;font-size: 14px;padding: 5px 4px 6px; transition-duration: 0.1s;}
			#dynamic-table tbody tr td:first-child{margin:0 auto;text-align: center;}
		</style>
			<div class="page-content">
				<div class="page-header">
	                <h1>
	                    <?php echo isset($info['id']) ? '编辑试卷' : '新增试卷'; ?>
	                </h1>
            	</div>

				<div class="row">
					<div class="col-xs-12" id="addExam">
						<form role="form" class="form-horizontal" novalidate="novalidate">
							<input type="hidden" name="id" id="id" value="<?php echo isset($info['id']) ? $info['id'] : ''; ?>">
							<div class="form-group">
								<label for="title" class="col-sm-2 control-label no-padding-right"> 标题 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="text" class="col-xs-10 col-sm-5" placeholder="试卷标题" id="title" name="title" value="<?php echo isset($info['title']) ? $info['title'] : ''; ?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 试卷简介 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<textarea class="form-controls" id="description" name="description" placeholder="试卷简介" cols="65" rows="3"><?php echo isset($info['description']) ? $info['description'] : ''; ?></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="libId" class="col-sm-2 control-label no-padding-right"> 所属题库 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<select id="libId" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="libId" multiple="multiple" data-placeholder="请选择所属题库">
											<?php foreach ($libList as $vo) : ?>
											<option value="<?php echo $vo['id']; ?>" <?php if(isset($info['lib_id']) && in_array($vo['id'], explode(',', $info['lib_id']))) echo "selected"; ?>><?php echo $vo['title']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 总分值 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="number" step="10" class="col-xs-10 col-sm-5" placeholder="总分值" id="total" name="total" value="<?php echo isset($info['total']) ? $info['total'] : ''; ?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">	
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 答题时间 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="number" step="10" class="col-xs-10 col-sm-5" placeholder="答题时间" id="limitTime" name="limitTime" value="<?php echo isset($info['limit_time']) ? $info['limit_time'] : ''; ?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">	
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 试题选型 </label>
								<div class="col-sm-10">
									<div class="radio">
										<label>
											<input type="radio" class="ace" name="itemChoose" value="1" <?php echo ! isset($info['item_choose']) || isset($info['item_choose']) && $info['item_choose'] ==1 ? 'checked="checked"' : ''; ?>>
											<span class="lbl"> 机选 </span>
										</label>									
										<label>
											<input type="radio" class="ace" name="itemChoose" value="2" <?php echo isset($info['item_choose']) && $info['item_choose'] ==2 ? 'checked="checked"' : ''; ?>>
											<span class="lbl"> 自选 </span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 题目数量 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="number" step="10" class="col-xs-10 col-sm-5" placeholder="题目数量" id="nums" name="nums" value="<?php echo isset($info['pro_num']) ? $info['pro_num'] : ''; ?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">	
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 试卷开放时间 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<div class="col-xs-10 col-sm-5" style="padding-left: 0;">
											<div class="input-daterange input-group">
												<input type="text" class="form-control datetime" name="start" value="<?php echo isset($info['start_time']) ? date('Y-m-d', $info['start_time']) : '' ?>"/>
												<span class="input-group-addon">
													<i class="fa fa-exchange"></i>
												</span>
												<input type="text" class="form-control datetime" name="end" value="<?php echo isset($info['end_time']) ? date('Y-m-d', $info['end_time']) : '' ?>" data-date-format="yyyy-mm-dd hh:ii"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="status" class="col-sm-2 control-label no-padding-right">状态</label>
								<div class="col-sm-10">
									<div class="radio">
										<label>
											<input type="radio" class="ace" name="status" value="1" <?php if(! isset($info['status']) || isset($info['status']) &&  $info['status'] == 1) echo 'checked="checked"'; ?>>
											<span class="lbl"> 正常 </span>
										</label>
										<label>
											<input type="radio" class="ace" name="status" value="3" <?php if(isset($info['status']) &&  $info['status'] == 3) echo 'checked="checked"'; ?>>
											<span class="lbl"> 结束 </span>
										</label>
										<label>
											<input type="radio" class="ace" name="status" value="2" <?php if(isset($info['status']) &&  $info['status'] == 2) echo 'checked="checked"'; ?>>
											<span class="lbl"> 待定 </span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="optionsWrap" class="col-sm-2 control-label no-padding-right"> 参与者 </label>
								<div class="controls col-sm-10">
									<div class="clearfix">
										<ul style="margin-left: 0;" class="recent-posts" id="optionsWrap">
											<li class="col-sm-10">
												<div class="clearfix">
													<select id="depart" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="depart" multiple="multiple" data-placeholder="可参与的部门">
														<?php echo $departList; ?>
													</select>													
												</div>
											</li>
											<li class="col-sm-10">
												<div class="clearfix">
													<select id="tag" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="tag" multiple="multiple" data-placeholder="可参与的标签">
														<?php foreach ($tagList as $vo) : ?>
														<option value="<?php echo $vo['id']; ?>" <?php if(isset($info['lib_id']) && in_array($vo['id'], $info['partake']['tag'])) echo "selected"; ?>><?php echo $vo['title']; ?></option>
														<?php endforeach; ?>
													</select>													
												</div>
											</li>
											<li class="col-sm-10">
												<div class="clearfix">
													<select id="users" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="users" multiple="multiple" data-placeholder="可参与的成员">
														
													</select>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div> 

	<!-- 						<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right">  </label>
								<div class="col-md-10">
									<a class="btn btn-info submit"><i class="ace-icon fa fa-check bigger-110"></i><span>提交</span></a>
									&nbsp; &nbsp; &nbsp;	
									<button type="reset" class="btn">
										<i class="ace-icon fa fa-undo bigger-110"></i>
										重置
									</button>							
								</div>
							</div> -->

							<input type="hidden" name="step" id="step" value="1"/>
						</form>
					</div>
				
					<!-- 题目列表 -->
					<div class="hidden" id="selecProblem">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th class="center sorting_disabled" width="5%">
										<label class="pos-rel">
											<input type="checkbox" class="ace" name="all">
											<span class="lbl"> </span>
										</label>
									</th>
									<th class="hidden-480" width="20%">问题名称</th>
									<th class="hidden-480" width="20%">所属题库</th>
									<th width="10%">类型</th>
									<th width="10%">分数</th>
									<th width="15%"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>创建时间</th>
								</tr>
							</thead>

							<tbody>
							</tbody>
						</table>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right">  </label>
						<div class="col-md-9">
							<a class="btn btn-info" id="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</a>
							&nbsp; &nbsp; &nbsp;	
							<button class="btn" id="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								<span>重置</span>
							</button>				
						</div>
					</div>

				</div>
			</div>

	</div>
</div>

<!-- <link rel="stylesheet" href="/static/base/css/bootstrap-datetimepicker.min.css" /> -->
<link rel="stylesheet" href="/static/base/css/datepicker.min.css" />

<script src="/static/base/js/chosen.jquery.min.js"></script>
<script src="/static/base/js/jquery.validate.min.js"></script>
<script src="/static/base/js/moment.min.js"></script>

<!-- <script src="/static/base/js/bootstrap-datetimepicker.min.js"></script> -->
<script src="/static/base/js/bootstrap-datepicker.min.js"></script>

{include file="layout/datatable" /}

<script type="text/javascript">

jQuery(function($){
	// 日期时间插件初始化
	// $('.datetime').datetimepicker().next().on(ace.click_event, function(){
	// 	$(this).prev().focus();
	// });
	// $('.datetime').datetimepicker({
	// 	// format: 'yyyy-mm-dd hh:ii',
	// 	autoclose: true,
	// 	todayHighlight: true,
	// 	language:'ch',
	// 	timepicker:false,
	// 	minuteStep: 10,
	// });
	$('.datetime').datepicker({
		format: 'yyyy-mm-dd',
		autoclose:true,
		startDate:'2014-01-01',
		endDate:'2050-12-12',
	});
	// fa-exchange
	
	// init multiple select chosen
	if(!ace.vars['touch']) {
		$('.chosen-select').chosen({allow_single_deselect:true}); 
		//resize the chosen on window resize

		$(window)
		.off('resize.chosen')
		.on('resize.chosen', function() {
			$('.chosen-select').each(function() {
				 var $this = $(this);
				 $this.next().css({'width': $this.parent().width()});
			})
		}).trigger('resize.chosen');
		//resize chosen on sidebar collapse/expand
		$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
			if(event_name != 'sidebar_collapsed') return;
			$('.chosen-select').each(function() {
				 var $this = $(this);
				 $this.next().css({'width': $this.parent().width()});
			})
		});
	}

	// 重置
	$("#reset").on('click', function(){
		var con  = $("#addExam");
		var step = parseInt($("#step").val());
		if (step == 2){
			con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
			con.find('input[name="step"]').val('1')
		} else {
			// $("#addExam form")[0].reset();
			window.location.reload();
		}
	});
	// 选题数量，自选是需要输入
	$('input[name=itemChoose]').on('click', function(){
		if ($(this).val() == 2){
			$("input[name=nums]").val('').parents('div.form-group').addClass('hidden');
		} else {
			$("input[name=nums]").parents('div.form-group').removeClass('hidden');
		}
	});

	// 保存方法
	$("#submit").on('click', function(){
		var con  = $("#addExam");
		var data = {};
		data.id 		= $.trim(con.find('input[name="id"]').val());
		data.title 		= $.trim(con.find('input[name="title"]').val());
		data.description= $.trim(con.find('input[name="description"]').val());
		data.status 	= parseInt(con.find('input[name="status"]:checked').val());
		data.start 		= $.trim(con.find('input[name="start"]').val());
		data.end 		= $.trim(con.find('input[name="end"]').val());
		data.total 		= $.trim(con.find('input[name="total"]').val());
		data.itemChoose = parseInt(con.find('input[name="itemChoose"]:checked').val());
		data.limitTime 	= $.trim(con.find('input[name="limitTime"]').val());
		data.nums 		= $.trim(con.find('input[name="nums"]').val());
		data.libId 		= con.find('select[name="libId"]').val();
		data.depart 	= con.find('select[name="depart"]').val();
		data.tag 		= con.find('select[name="tag"]').val();
		data.users 		= con.find('select[name="users"]').val();
		data.limitTime 	= data.limitTime > 0 ? data.limitTime : 0;
		data.problem 	= [];
		var step 		= parseInt(con.find('input[name="step"]').val());
		var tag = false;
		if ($.isEmptyObject(data.title))
		{
			con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
			bootbox.alert("试卷标题不能为空");
			return false;
		}
		if ($.isEmptyObject(data.libId))
		{
			con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
			bootbox.alert("请选择所属题库");
			return false;
		}

		if ($.isEmptyObject(data.total) && data.itemChoose == 1)
		{
			con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
			bootbox.alert("总分值不能为空");
			return false;
		}

		if ($.isEmptyObject(data.limitTime))
		{
			con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
			bootbox.alert("答题时间不能为空");
			return false;
		}
		if (data.itemChoose == 1)
		{
			if ($.isEmptyObject(data.nums)){
				con.removeClass('hidden').siblings("#selecProblem").addClass('hidden');
				bootbox.alert("机选试题题目数量不能为空");
				return false;
			}

			saveData(data)
		} 
		if (data.itemChoose == 2)
		{
			if (step == 1)
			{
				con.find('input[name="step"]').val('2')
				con.addClass('hidden').siblings("#selecProblem").removeClass('hidden');
				initDynamicTable(data.libId);
				return false;
			} 
			if (step == 2)
			{
				data.problem = checkedLines;
				if (data.problem.length < 1){
					bootbox.alert('请选择题目');
					return false;
				}
				
				bootbox.confirm('试卷总分将以实际选择为准，是否继续提交？', function(result){
					if (result){
						data.nums  = data.problem.length;
						saveData(data);
					} 
				});
				
				saveData(data);
			}
		}

		return false;
	});

	function saveData(data)
	{	//console.log(data);
		$.ajax({
			url:'/home/exam/saveexam',
			type:'post',
			data: data,
			dataType:'json',
			success:function(res){
				if (res.result == 1){
					bootbox.alert(res.message, function(){
						window.location.href="/home/exam/index";
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

	// init Dynamic Table 
	var sid = '<?php echo isset($info['que_opt']) ? implode('~', $info['que_opt']) : '';?>';
	var checkedLines = [<?php echo isset($info['que_opt']) ? implode(',', $info['que_opt']) : '';?>];
	function initDynamicTable(data) {
		var oTable1 = $('#dynamic-table')
		.wrap("<div class='dataTables_borderWrap' />")
		.dataTable({
			"aoColumns": [
				{ "bSortable": false},
				null, null,null, null, 
				{ "bSortable": false }
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
			"bProcessing": true, //是否ajax资源
			"sAjaxSource": '/home/exam/getexamproblems/id/' + data.join('~') + '/sid/' + sid, //ajax路径
			"sServerMethod": "GET",
			"oLanguage": {
				"sLengthMenu": "每页显示 _MENU_条",
				"sZeroRecords": "没有找到符合条件的数据",
				// "sProcessing": "&lt;img src='/public/static/base/img/loading.gif' /&gt;",
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
			},
			"fnDrawCallback": initSelect,
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
		});

		$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
			var th_checked = this.checked;//checkbox inside "TH" table header
			var val = 0, valIndex = -1;
			$(this).closest('table').find('tbody > tr').each(function(){
				var row = this;
				val = parseInt($(this).children('td:first-child').find('input[type=checkbox]').val());
				valIndex = $.inArray(val, checkedLines);
				if(th_checked){
					tableTools_obj.fnSelect(row);
					if (valIndex == -1) {
						checkedLines.push(val);
					} 
				} else {
					tableTools_obj.fnDeselect(row);
					if (valIndex > -1) {
						checkedLines.splice(valIndex, 1);
					}
				} 
				// console.log(checkedLines);
				// console.log(checkedTotal);
			});
		});

		$('#dynamic-table').on('click', 'td input[type=checkbox]', function(){
			var row = $(this).closest('tr').get(0);
			var val = parseInt($(this).val());
			var valIndex = $.inArray(val, checkedLines);
			// console.log($(this));
			// console.log(this.checked);
			if(!this.checked) { 
				tableTools_obj.fnSelect(row);
				if (valIndex == -1) {
					checkedLines.push(val);
				} 
			} else {
				tableTools_obj.fnDeselect($(this).closest('tr').get(0));
				if (valIndex > -1){
					checkedLines.splice(valIndex, 1);
				} 
			}
			// console.log(checkedLines);
		});

		function initSelect()
		{
			if (checkedLines.length < 1) {
				return;
			}
			var val = 0, score = 0;
			$('#dynamic-table > tbody > tr').each(function(){
				val = parseInt($(this).children('td:first-child').find('input[type=checkbox]').val());
				var valIndex = jQuery.inArray(val, checkedLines);
				if (valIndex > -1) {
					$(this).children('td:first-child').find('input[type=checkbox]').get(0).checked = true;
					$(this).addClass('success');
				}
			})

		}

	}
})


</script>