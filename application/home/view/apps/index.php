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
					<a href="#">应用列表</a>
				</li>
			</ul>
		</div>
		<style type="text/css">
			.table-header h4 a {margin-right:1%;float: right;color: #fff;}
		</style>
		<div class="page-content row">
			<div class="col-xs-12 widget-box widget-color-blue2">
				<div class="table-header widget-header">
					<h4>应用列表
						<a href="/home/apps/appadd/">添加应用</a>
					</h4>
				</div>
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="15%">名称</th>
								<th width="15%">图标</th>
								<th width="15%">Controller</th>
								<th width="7%">安装次数</th>
								<th width="7%">状态</th>
								<th width="10%">
									<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
									添加时间
								</th>
								<th width="20%" class="sorting_disabled"></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($appList as $vo):?>
							<tr>
								<td><?php echo $vo['app_name']; ?></td>	
								<td><?php if (! empty($vo['app_logo'])): ?><img src="<?php echo $vo['app_logo']; ?>" style="width:80px;height: 80px;"/><?php endif; ?></td>
								<td><?php echo $vo['app_controller']; ?></td>
								<td><?php echo $vo['install_times']; ?></td>							
								<td><?php if ($vo['status'] == 1):?>
									<span class="btn btn-minier btn-info close-qychat" data="<?php echo $vo['id'];?>"  data-rel="tooltip" title="点击关闭" >开启</span>
									<?php elseif($vo['status'] == 0):?>
									<span class="btn btn-minier btn-warning open-qychat" data="<?php echo $vo['id'];?>" data-rel="tooltip" title="点击开启">关闭</span>
									<?php else:?>
									<span class="btn btn-minier">已删除</span>
									<?php endif;?>
								</td>
								<td>
									<?php echo date('Y-m-d H:i:s', $vo['create_time']);?>
								</td>
								<td class="center">
									<div class="hidden-sm hidden-xs btn-group">
										<a href="/home/apps/appadd/id/<?php echo $vo['id'];?>" class="btn btn-xs btn-info">
											<i class="ace-icon fa fa-edit bigger-120"></i>
											编辑
										</a>										
										<button class="btn btn-xs btn-danger btn-delete" data="<?php echo $vo['id'];?>">
											<i class="ace-icon fa fa-trash-o bigger-120"></i>
											删除
										</button>
										<a href="/home/apps/menulist/id/<?php echo $vo['id'];?>" class="btn btn-xs btn-success">
											<i class="ace-icon fa fa-list bigger-120"></i>
											菜单
										</a>
										<a href="/home/apps/menuadd/pid/<?php echo $vo['id'];?>" class="btn btn-xs btn-purple">
											<i class="ace-icon fa fa-pencil bigger-120"></i>
											添加菜单
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
			  null, null, null,null, null, null, null
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
			bootbox.confirm('您确定要删除此应用吗？', function(res) {
				if (res) {
					$.ajax({
						url: '/home/apps/appdelete/id/' + id,
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
