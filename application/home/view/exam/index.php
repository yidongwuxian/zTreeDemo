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
					<a href="#">试卷列表</a>
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
					<h4>试卷列表
						<a href="/home/exam/addexam/">新增问卷</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="15%">问卷名称</th>
								<th width="15%">所属题库</th>
								<th width="15%">调查对象</th>
								<th width="7%">试题选型</th>
								<th width="7%">总分</th>
								<th width="15%">
									<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
									起始时间
								</th>
								<th width="7%">状态</th>
								<th width="20%" class="sorting_disabled">操作</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($examList as $vo):?>
							<tr>
								<td><?php echo $vo['title']; ?></td>	
								<td><?php echo $vo['lib_name']; ?></td>
								<td><?php echo $vo['partakeInfo']; ?></td>
								<td><?php echo $vo['item_choose'] == 1 ? '机选' : '自选';?></td>	
								<td><?php echo $vo['total']; ?></td>								
								<td >
									<?php echo date('Y-m-d H:i', $vo['start_time']) . ' ~ ' . date('Y-m-d H:i', $vo['end_time']);?>
								</td>
								<td>
									<?=(time() > $vo['end_time'] || $vo['status']==3) ? '已结束' : (($vo['status']==1 && time() <= $vo['end_time'] && time() >= $vo['start_time']) ? '开启中': '未开启')  ?>
								</td>
								<td>
									<div class="btn-group">
										<a class="btn btn-xs btn-info" href="/home/exam/examproblems/id/<?php echo $vo['id'];?>" title="试卷题目信息">
											<i class="ace-icon fa fa-list"></i>
											试题
										</a>
										<a class="btn btn-xs btn-purple" href="/home/exam/examanswer/id/<?php echo $vo['id'];?>" title="答题信息">
											<i class="ace-icon fa fa-list"></i>
											答题详情
										</a>
										<a class="btn btn-xs btn-primary editBtn" href="/home/exam/getexaminfo/id/<?php echo $vo['id'];?>" role="button" title="编辑">
											<i class="ace-icon fa fa-edit"></i>
											编辑
										</a>
										<a href="javascript:;" class="btn btn-xs btn-danger btn-delete" title="删除">
											<i class="ace-icon fa fa-trash-o"></i>
											删除
										</a>
										<a class="btn btn-xs btn-success" href="/home/exam/export/id/<?php echo $vo['id'];?>" title="导出报表">
											<i class="ace-icon fa fa-share"></i>
											导出
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


	</div>
</div>
{include file="layout/datatable" /}
<script type="text/javascript">
	jQuery(function($) {

		// datatable init
		var oTable1 = $('#dynamic-table')
		.wrap("<div class='dataTables_borderWrap' />")
		.dataTable({
			"aoColumns": [
			  null, null, null,null, null, null, null, null
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
			// "bProcessing": false, //是否ajax资源
			// "sAjaxSource": 'url', //ajax路径
			// "sServerMethod": "GET",
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

		// 删除题库
		$("#dynamic-table .btn-delete").on('click', function(){
			var id  = $(this).siblings('input').val();
			bootbox.confirm('一旦删除不能恢复！您确定要删除吗？', function(result){
				if(result){
					$.ajax({
						url:'/home/exam/deleteexam/id/' + id,
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



	})


</script>
