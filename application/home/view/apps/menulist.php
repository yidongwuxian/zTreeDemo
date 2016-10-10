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
					<a href="/home/apps/index">应用列表</a>
				</li>
				<li>
					<a href="#">菜单列表</a>
				</li>
			</ul>
		</div>
		<style type="text/css">
			.table-header h4 a {margin-right:1%;float: right;color: #fff;}
		</style>
		<div class="page-content row">
			<div class="col-xs-12 widget-box widget-color-blue2">
				<div class="table-header widget-header">
					<h4>菜单列表
						<a href="/home/apps/index/">返回应用列表</a>
						<a href="/home/apps/menuadd/pid/<?php echo $appInfo['id']; ?>">添加菜单</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="15%">应用名称</th>
								<th width="15%">父菜单ID</th>
								<th width="15%">菜单名称</th>
								<th width="7%">菜单类型</th>
								<th width="7%">CODE</th>
								<th width="10%">排序</th>
								<th width="10%">级别</th>
								<th width="20%" class="sorting_disabled"></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($menuList as $vo):?>
							<tr>
								<td><?php echo $appInfo['app_name']; ?></td>	
								<td><?php echo $vo['pid'] == 0 ? '无' : $vo['pid'] ; ?></td>
								<td><?php echo $vo['name']; ?></td>
								<td><?php echo $vo['type']; ?></td>							
								<td><?php echo $vo['code']; ?></td>
								<td><?php echo $vo['sort']; ?></td>
								<td><?php echo $vo['level']; ?></td>
								<td class="center">
									<div class="hidden-sm hidden-xs btn-group">
										<a href="/home/apps/menuadd/pid/<?php echo $appInfo['id']; ?>/id/<?php echo $vo['id'];?>" class="btn btn-xs btn-info">
											<i class="ace-icon fa fa-edit bigger-120"></i>
											编辑
										</a>										
										<button class="btn btn-xs btn-danger btn-delete" data="<?php echo $vo['id'];?>">
											<i class="ace-icon fa fa-trash-o bigger-120"></i>
											删除
										</button>
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
			"bProcessing": false,
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

		//删除
		$(".btn-delete").on('click', function() {
			var id = $(this).attr('data');
			var con = $(this);
			bootbox.confirm('您确定要删除此菜单吗？', function(res) {
				if (res) {
					$.ajax({
						url: '/home/apps/menudelete/id/' + id,
						dataType: 'json',
						type:'get',
						success: function(res) {
							if (res.result == 1) {
								con.parents('tr').remove();
							} else {
								bootbox.alert(res.message);
							}
						}
					});
				}
			})
		});



	})


</script>
