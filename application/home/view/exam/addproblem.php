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
					<a href="#"><?php echo isset($info['id']) ? '编辑题目' : '新增题目'; ?></a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>
		<style type="text/css">
		.ace-spinner{width:42% !important;}
		#optionsWrap .ace-spinner{width:18% !important;}
		</style>
			<div class="page-content">
				<div class="page-header">
	                <h1>
	                    <?php echo isset($info['id']) ? '编辑题目' : '新增题目'; ?>
	                </h1>
            	</div>
				<div class="row">
					<div class="col-xs-12">
						<form role="form" class="form-horizontal" method="post" novalidate="novalidate" id="addProblem">
							<input type="hidden" name="id" id="id" value="<?php echo isset($info['id']) ? $info['id'] : ''; ?>">
							<div class="form-group">
								<label for="title" class="col-sm-2 control-label no-padding-right"> 题目 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="text" class="col-xs-10 col-sm-5" placeholder="问题名称" id="title" name="title" value="<?php echo isset($info['title']) ? $info['title'] : ''; ?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="score" class="col-sm-2 control-label no-padding-right"> 总分 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<input type="number" class="col-xs-10 col-sm-5" placeholder="题目总分值" id="score" name="score" value="<?php echo isset($info['score']) ? $info['score'] : ''; ?>">	
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="libId" class="col-sm-2 control-label no-padding-right"> 所属题库 </label>
								<div class="col-sm-10">
									<div class="clearfix">
										<select id="libId" class="col-xs-10 col-sm-5" name="libId"><!-- multiple="multiple"  -->
											<?php foreach ($libList as $vo) : ?>
											<option value="<?php echo $vo['id']; ?>" <?php echo $libId && $libId == $vo['id'] ? 'selected' :''; ?>><?php echo $vo['title']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="type" class="col-sm-2 control-label no-padding-right"> 问题类型 </label>
								<div class="col-sm-10">
									<div class="radio">
										<label>
											<input type="radio" class="ace" name="type" value="1" <?php echo ! isset($info['type']) || isset($info['type']) && $info['type'] ==1 ? 'checked="checked"' : ''; ?>>
											<span class="lbl"> 单选 </span>
										</label>									
										<label>
											<input type="radio" class="ace" name="type" value="2" <?php echo isset($info['type']) && $info['type'] ==2 ? 'checked="checked"' : ''; ?>>
											<span class="lbl"> 多选 </span>
										</label>
									</div>
								</div>
							</div>
							<style type="text/css">
								ul#optionsWrap li.col-sm-10 {list-style: none;margin-bottom: 15px;padding-left:0; }ul#optionsWrap li input{margin-right: 5%;}
								ul#optionsWrap li i{cursor: pointer;padding:8px; } ul#optionsWrap li .help-block{float:left; position:relative;}
							</style>
							<div class="form-group">
								<label for="optionsWrap" class="col-sm-2 control-label no-padding-right"> 选项 </label>
								<div class="controls col-sm-10">
									<div class="clearfix">

										<ul style="margin-left: 0;" class="recent-posts" id="optionsWrap">
											<?php  if (isset($info['options'])) { foreach ($info['options'] as $key => $value):?>
											<li class="col-sm-10">
												<div class="clearfix">
													<input type="text" class="col-sm-3" id="seoptions[<?php echo $key; ?>][title]" name="seoptions[<?php echo $key; ?>][title]" placeholder="选项标题"  value="<?php echo $value['title']; ?>">
													<input type="number" id="seoptions[<?php echo $key; ?>][score]" name="seoptions[<?php echo $key; ?>][score]" placeholder="选项分值" value="<?php echo $value['score']; ?>" class="col-sm-2">
												</div>
											</li>
											<?php endforeach; } else { ?>
											<li class="col-sm-10">
												<div class="clearfix">
													<input type="text" class="col-sm-3" id="seoptions[0][title]" name="seoptions[0][title]" placeholder="选项标题">
													<input type="number" id="seoptions[0][score]" name="seoptions[0][score]" placeholder="选项分值" class="col-sm-2">															
												</div>
											</li>
											<li class="col-sm-10">
												<div class="clearfix">
													<input type="text" class="col-sm-3" id="seoptions[1][title]" name="seoptions[1][title]" placeholder="选项标题">
													<input type="number" id="seoptions[1][score]" name="seoptions[1][score]" placeholder="选项分值" class="col-sm-2">
												</div>
											</li>
											<?php } ?>
											<li class="col-sm-10">
												<button type="button" class="btn btn-sm btn-success" id="addOption">增加选项</button>	
											</li>
										</ul>
									</div>
								</div>
							</div> 
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right">  </label>
								<div class="col-md-10">
									<button class="btn btn-info" id="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</button>
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
			</div>

	</div>
</div>
<script src="/static/base/js/jquery.validate.min.js"></script>
<script src="/static/base/js/fuelux.spinner.min.js"></script>
<script type="text/javascript">

jQuery(function($){
	//
	// $('#score').ace_spinner({value:0,min:0,max:100,step:1})
	// .closest('.ace-spinner')
	// .on('changed.fu.spinbox', function(){}); 
	// $('#optionsWrap input.scoreopt').ace_spinner({value:0,min:0,max:100,step:1, on_sides: true, icon_up:'ace-icon fa fa-plus', icon_down:'ace-icon fa fa-minus'});
	// 增加选项
	$("#addOption").on('click', function(){
		var index = $("#addProblem ul > li").length - 1;
		var newOptHtml = '<li class="col-sm-10"><div class="clearfix">'+
							'<input type="text" class="col-sm-3" id="seoptions['+index+'][title]" name="seoptions['+index+'][title]" placeholder="选项标题">'+
							'<input type="number" class="col-sm-2" id="seoptions['+index+'][score]" name="seoptions['+index+'][score]" placeholder="选项分值">'+
							'<a><i title="删除" class="menu-icon fa fa-times red2">&nbsp</i></a></div>'+
						'</li>';
		$(this).parents('li').before(newOptHtml);
	});

	// 删除增加的选项
	$("#addProblem").on('click', 'ul > li a', function(){
		$(this).parents('li').remove();
	});

	// 保存方法
	function save(){
		var con  = $("#addProblem");
		var data = {};
		data.id 	= $.trim(con.find('input[name="id"]').val());
		data.title 	= $.trim(con.find('input[name="title"]').val());
		data.score	= 0;
		data.libId	= con.find('select[name="libId"]').val();
		data.type 	= con.find('input[name="type"]:checked').val();
		data.seopt  = [];

		var tmpTitle, tmpScore, answer = [], options;

		con.find("ul li:not(ul li:last-child)").each(function(index, el) {
			options = {};
			tmpTitle = $(el).find("input:first-child").val();
			tmpScore = $(el).find("input:last-child").val();
			tmpScore = tmpScore > 0 ? tmpScore : 0;
			if (tmpTitle != '')
			{
				data.score = parseFloat(data.score) + tmpScore;
				options.title = tmpTitle;
				options.score = tmpScore;
				data.seopt.push(options);
				if (tmpScore > 1)
				{
					answer.push(tmpTitle);
				}
			}
		});

		if (data.seopt.length < 2 || data.score < 1 || (data.type ==1 && answer.length != 1)){
			bootbox.alert("题目选项设置错误");
			return false;
		}

		// console.log(data);

		$.ajax({
			type: "post",
			url: '/home/exam/saveProblem/',
			data: data,
			dataType: "json",
			success: function(res) {
				if (res.result == 1){
					bootbox.confirm("操作成功！是否继续添加题目？", function (result) {  
		                if(result) {  
		                    $('#addProblem')[0].reset();
		                } else {  
		                    window.location.href="/home/exam/problem/id/" + data.libId;
		                }  
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
		debug:true,
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: true,
		ignore: ".ignore",
		rules: {
			title: {
				required: true,
				minlength: 2,
				maxlength:100
			},
			score: {
				required: true,
				number:true,
				min:0,
				max:100
			},
			libId: {
				required: true,
			},
		},
		messages: {
			title: {
				required: "题库名称不能为空",
				minlength: "动动手指头再加俩字吧",
				maxlength: "囧 标题需要精简"
			},
			score: {
				required: "请输入该题目分值",
				number:"请输入数字",
				min:"分值不能为负数",
				max:"这分值太大了"
			},
			libId: {
				required: "请选择所属题库",
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
			save();
		},
		invalidHandler: function (form) {
		}
	});
	

})


</script>