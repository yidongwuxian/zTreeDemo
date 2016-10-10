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
		<div class="page-content">
			<div class="page-header">
				<h1>
					应用管理
				</h1>
			</div>
			<style type="text/css">
				.ace-thumbnails > li {margin:15px;}
				.ace-thumbnails > li .tags, .ace-thumbnails > li .tags > .label-holder, .ace-thumbnails > li .tags > .label-holder .label{width:150px !important;height: 32px;font-size: 16px;line-height: 24px;} 
				.ace-thumbnails > li {border:1px solid #333;}
				.ace-thumbnails > li > .tools.tools-bottom {bottom: -34px; height: 32px;}
				.ace-thumbnails > li:hover > .tools.tools-bottom{bottom: -1px;}
				.installed { background: #81ba53 none repeat scroll 0 0; border-radius: 2px; color: #fff; padding: 3px; position: absolute; right: 0; top: 0px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div>
						<ul class="ace-thumbnails clearfix">
							<?php foreach ($appList as $vo):?>
							<li>
								<a href="<?php echo in_array($vo['id'], $qyAppIds) ? '/home/' . $vo['app_controller'] . '/index/' : '#'; ?>" class="cboxElement">
									<img width="150" height="150" alt="150x150" src="<?php echo file_exists(ROOT_PATH . '/public' . $vo['app_logo']) ? $vo['app_logo'] : 'http://placehold.it/150x150'; ?>" />
									<div class="tags">
										<span class="label-holder">
											<span class="label label-inverse"><?php echo $vo['app_name']; ?></span>
										</span>
									</div>
								</a>
								<?php if(in_array($vo['id'], $qyAppIds)): ?>
								<span class="installed">已安装</span>	
								<?php else: ?>
								<div class="tools tools-top">
									<a href="#modal-install" data-toggle="modal" data="<?php echo $vo['id'];?>">安装</a>
								</div>
								<?php endif; ?>					
							</li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>

				<style type="text/css">.modal-body input{width:90%;}</style>
				<div id="modal-install" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header no-padding">
								<div class="table-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
										<span class="white">&times;</span>
									</button>
									应用安装
								</div>
							</div>

							<div class="modal-body">
								<form class="form-horizontal" role="form" action="" method="post" id="adddepart">
									<input type="hidden" id="appId">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="agentId">微信端应用ID</label>
										<div class="col-sm-9">
											<input type="text" id="agentId" name="agentId" placeholder="微信端创建的对应应用ID" class="col-xs-10 col-sm-5" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>		
										</div>
									</div>
									<div class="form-group hidden">
										<label class="col-sm-3 control-label no-padding-right" for="url">URL</label>
										<div class="col-sm-9">
											<input type="text" id="url" name="url" placeholder="URL" class="col-xs-10 col-sm-5" readonly/>			
										</div>
									</div>
									<div class="form-group hidden">
										<label class="col-sm-3 control-label no-padding-right" for="token">Token</label>
										<div class="col-sm-9">
											<input type="text" id="token" name="token" placeholder="Token" class="col-xs-10 col-sm-5" readonly/>			
										</div>
									</div>
									<div class="form-group hidden">
										<label class="col-sm-3 control-label no-padding-right" for="aeskey">AESKey</label>
										<div class="col-sm-9">
											<input type="text" id="aeskey" name="aeskey" placeholder="AESKey" class="col-xs-10 col-sm-5" readonly/>			
										</div>
									</div>
									<div class="hidden" style="text-align: center;margin:10px auto;">
										备注：请复制URL、Token、AESKey到微信端“回调模式”的相应配置项。
									</div>
								</form>
							</div>
							<div class="modal-footer no-margin-top">
								<button class="btn btn-sm" data-dismiss="modal">
									<i class="ace-icon fa fa-times"></i>
									取消
								</button>
								<button id="confirm" class="btn btn-sm btn-primary">
									<i class="ace-icon fa fa-check"></i>
									确认
								</button>
								<button id="install_submit" class="btn btn-sm btn-primary hidden">
									<i class="ace-icon fa fa-check"></i>
									保存
								</button>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	
			

	</div>
</div>
<link rel="stylesheet" href="/static/base/css/colorbox.min.css" />
<script src="/static/base/js/jquery.colorbox.min.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		var $overflow = '';
		var colorbox_params = {
			rel: 'colorbox',
			reposition:true,
			scalePhotos:true,
			scrolling:false,
			previous:'<i class="ace-icon fa fa-arrow-left"></i>',
			next:'<i class="ace-icon fa fa-arrow-right"></i>',
			close:'&times;',
			current:'{current} of {total}',
			maxWidth:'100%',
			maxHeight:'100%',
			onOpen:function(){
				$overflow = document.body.style.overflow;
				document.body.style.overflow = 'hidden';
			},
			onClosed:function(){
				document.body.style.overflow = $overflow;
			},
			onComplete:function(){
				$.colorbox.resize();
			}
		};

		$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);

		$(document).one('ajaxloadstart.page', function(e) {
			$('#colorbox, #cboxOverlay').remove();
	    });

		// 点击安装应用
		$(".ace-thumbnails > li .tools a").on('click', function(){
			var con = $("#modal-install");
			con.find("form")[0].reset();
			con.find("#appId").val($(this).attr('data')).siblings('div:first').removeClass('hidden').siblings('div').addClass('hidden');
			con.find("#install_submit").addClass('hidden').siblings('#confirm').removeClass('hidden');
			con.find("#agentId").removeAttr("readonly");
		});

		// 回调信息
		$("#modal-install #confirm").on('click', function(){
			var data 	= {};
			var con 	= $("#modal-install");
			data.appId 	= $("#appId").val();
			data.agentId= parseInt($("#agentId").val());
			if (data.agentId == "" || data.agentId < 1) {
				return false;
			}
			if (data.appId == "") {
				bootbox.alert("请求参数有误，请重试", function(){
					window.location.reload();
				});
				return false;
			}

			$.ajax({
				url:"/home/qyapp/setapp/",
				type:"post",
				dataType:"json",
				data:data,
				success:function(res) {
					// console.log(res);
					if (res.result == 1) {
						con.find("#url").val(res.extension.url);
						con.find("#token").val(res.extension.token);
						con.find("#aeskey").val(res.extension.aeskey);
						con.find('#appId').siblings('div').removeClass('hidden');
						$("#confirm").addClass('hidden').siblings('#install_submit').removeClass('hidden');
						con.find('#agentId').attr("readonly","readonly");
					} else {
						bootbox.alert(res.message);
					}
					return false;
				},
				error:function(){
					bootbox.alert("请求出错", function(){
						window.location.reload();
					});
				}
			});

		});
		
		$("#install_submit").on('click', function(){
			var data 	= {};
			data.appId 	= $("#appId").val();
			data.agentId= $("#agentId").val();

			if (data.appId == "") {
				bootbox.alert("请求参数有误，请重试", function(){
					window.location.reload();
				});
				return false;
			}
			if (data.agentId == "") {
				bootbox.alert("请输入微信端应用ID");
				return false;
			}
			// console.log(data);
			$.ajax({
				url:"/home/qyapp/install/",
				type:"post",
				dataType:"json",
				data:data,
				success:function(res) {
					bootbox.alert(res.message, function(){
						if (res.result == 1) {
							window.location.reload();
						}
					});	
					return false;
				},
				error:function(){
					bootbox.alert("请求出错");
					return false;
				}
			});
		});
	})
</script>
