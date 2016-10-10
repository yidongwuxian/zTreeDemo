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
					<h4>答卷详情
						<a href="/home/exam/index/">返回试卷列表</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="15%">部门</th>
								<th width="15%">答卷人</th>
								<th width="15%">耗时</th>
								<th width="7%">分数</th>
								<th width="15%">
									<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
									答题时间
								</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($examAnswerList as $vo):?>
							<tr>
								<td><?php echo $vo['depart']; ?></td>	
								<td><?php echo $vo['userName']; ?></td>	
								<td><?php echo $vo['timeCost']; ?></td>
								<td><?php echo $vo['total']; ?></td>								
								<td >
									<?php echo date('Y-m-d H:i', $vo['start_time']);?>
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
			  null, null, null,null, null
			],
			"paging": true,
			"bAutoWidth": false,
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bStateSave": true, //状态保存
			"bDestroy":false,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bInfo": true,//页脚信息
			"bProcessing": false, //是否ajax资源
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

	})


</script>
