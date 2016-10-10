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
					<a href="#">新增题库</a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>

		<div class="tabbable">
			<div class="page-content">
				<div class="page-header">
	                <h1>
	                    新增题库
	                </h1>
            	</div>
				<div class="row">
					<div class="col-xs-12">
						<form role="form" class="form-horizontal" method="post" novalidate="novalidate" id="addProblem">
							<input type="hidden" name="id" id="id" value="<?php echo isset($info['id']) ? $info['id'] : ''; ?>">
							<div class="form-group">
								<label for="title" class="col-sm-2 control-label no-padding-right">题库名称</label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input class="col-xs-10 col-sm-5" type="text" id="title" name="title" placeholder="题库名称">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="description" class="col-sm-2 control-label no-padding-right">题库简介</label>
								<div class="col-sm-10">
									<div class="clearfix">
										<textarea class="form-controls" id="description" name="description" placeholder="题库简介" cols="38" rows="3"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="status" class="col-sm-2 control-label no-padding-right">状态</label>
								<div class="col-sm-10">
									<div class="radio">
										<label>
											<input type="radio" class="ace" name="status" value="1" checked="checked">
											<span class="lbl"> 正常 </span>
										</label>
										<label>
											<input type="radio" class="ace" name="status" value="2">
											<span class="lbl"> 停用 </span>
										</label>
										<label>
											<input type="radio" class="ace" name="status" value="3">
											<span class="lbl"> 废弃 </span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="status" class="col-sm-2 control-label no-padding-right"> </label>
								<div class="col-md-10">
									<button class="btn btn-info"><i class="ace-icon fa fa-check bigger-110"></i>提交</button>	
									&nbsp; &nbsp; &nbsp;	
									<button type="reset" class="btn">
										<i class="ace-icon fa fa-undo bigger-110"></i>
										重置
									</button>						
								</div>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.page-content -->	

		</div>

	</div>
</div>
<script src="/static/base/js/jquery.validate.min.js"></script>

<script type="text/javascript">

jQuery(function($){
	// 保存方法
	function saveLib(){
		var data = {};
		var con  = $('#addProblem');
		data.id 	= 0;
		data.title 	= $.trim(con.find('input[name="title"]').val());
		data.status	= con.find('input[name="status"]').val();
		data.description = $.trim(con.find('textarea[name="description"]').val());

		$.ajax({
			type: "post",
			url: '/home/exam/savelib/',
			data: data,
			dataType: "json",
			success: function(res) {
				if (res.result == 1){
					bootbox.alert('操作成功', function(){
						window.location.href='/home/exam/lib/';
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
	$('#addProblem').validate({
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
	

})


</script>