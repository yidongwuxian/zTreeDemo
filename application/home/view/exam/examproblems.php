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
					<a href="#">试卷题目详情</a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>

		<div class="page-content row">
			<div class="col-xs-12 widget-box widget-color-blue2">
				<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
				<div class="table-header widget-header">
					<h4>
						试卷 <a class="white" href="/home/exam/getexaminfo/id/<?php echo $info['id'];?>">“<?php echo $info['title']; ?>”</a> 的题目列表
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th></th>
								<th class="hidden-480" width="20%">问题名称</th>
								<th class="hidden-480" width="20%">所属题库</th>
								<th width="10%">类型</th>
								<th width="10%">分数</th>
								<th>选项</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($info['questions'] as $key => $vo):?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td><?php echo $vo['title']; ?></td>	
								<td><?php echo isset($libList[$vo['lib_id']]) ? $libList[$vo['lib_id']] : '题库出错' .$vo['lib_id'] ; ?></td>
								<td><?php echo $vo['type'] == 1 ? '单选' : '多选';?></td>									
								<td><?php echo $vo['score'];?></td>
								<td>
									<!-- <span>选项详情</span> -->
									<table><!--  class="hidden" -->
									<?php foreach ($vo['options'] as $key => $value) { ?>
									<tr>
										<td width="10%">选项 <?php echo $key + 1; ?></td>
										<td width="60%"><?php echo $value['title'];  ?></td>
										<td width="10%"><?php echo $value['score']; ?>分</td>
									</tr>
									<?php } ?>
									</table>
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
				null, null,null, null, null,
			],
			"aaSorting": [],			
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


		$("#dynamic-table td>span").hover(function(){
			$(this).siblings('table').removeClass('hidden')
		},function(){
			$(this).siblings('table').addClass('hidden')
		});

	})


</script>
