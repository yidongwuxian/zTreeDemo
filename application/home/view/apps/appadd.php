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
					<a href="#">添加应用</a>
				</li>
			</ul>
		</div>

		<div class="page-content">						
			<div class="page-header">
				<h1>添加应用</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
				<form class="form-horizontal" role="form" action="/admin/apps/appsave" method="post" id="save">
					<input type="hidden" id="id" name="id" value="<?php echo isset($info['id']) ? $info['id'] : 0; ?>"/>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="name">应用名称</label>
						<div class="col-sm-10">
							<input type="text" id="name" name="name" value="<?php echo isset($info['app_name']) ? $info['app_name'] : ''; ?>" placeholder="应用名称" class="col-xs-10 col-sm-5" maxlength="10"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="controller">应用管理Controller</label>
						<div class="col-sm-10">
							<input type="text" id="controller" name="controller" value="<?php echo isset($info['app_controller']) ? $info['app_controller'] : ''; ?>" placeholder="应用管理Controller" class="col-xs-10 col-sm-5" maxlength="100"/>
						</div>
					</div>				
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="sort">应用排序</label>
						<div class="col-sm-10">
							<input type="number" id="sort" name="sort" value="<?php echo isset($info['sort']) ? $info['sort'] : ''; ?>" placeholder="应用排序（数值越大越靠前）" class="col-xs-10 col-sm-5" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="desc">应用描述</label>
						<div class="col-sm-10">
							<textarea rows="3" cols="60" placeholder="应用描述" name="desc"  class="col-xs-10 col-sm-5"><?php echo isset($info['app_desc']) ? $info['app_desc'] : ''; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="banner-img">应用LOGO</label>
						<div class="col-sm-10">
							<img style="width:120px;height:120px" alt="应用LOGO" id="banner-img" src="<?php echo isset($info['logo']) ? $info['app_logo'] : 'http://placehold.it/120x120'; ?>" /><br/>
							<input type="hidden" id="banner" name="logo" value="<?php echo isset($info['app_logo']) ? $info['app_logo'] : ''; ?>">		
							<input type="file" id="file-banner" name="file-banner" class="banner-upload <?php echo isset($info['logo']) ? 'hidden' : ''; ?>"  />
							<a href="javascript:;" class="hover <?php echo isset($info['logo']) ? '' : 'hidden'; ?>" id="delete">删除重新上传</a>	
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="memo">应用备注</label>
						<div class="col-sm-10">
							<textarea rows="3" cols="60" placeholder="应用备注" name="memo" class="col-xs-10 col-sm-5"><?php echo isset($info['memo']) ? $info['memo'] : ''; ?></textarea>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="status">应用状态</label>
						<div class="col-sm-10">
							<label>
								<input name="status" id="status" class="ace ace-switch" type="checkbox" <?php if(isset($info['status']) && $info['status'] == 1){ echo  "checked='checked'";} ?>/>
								<span class="lbl"></span>
							</label>
						</div>
					</div>					
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right">  </label>
						<div class="col-md-9">
							<a class="btn btn-info" id="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</a>
							&nbsp; &nbsp; &nbsp;	
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								<span>重置</span>
							</button>				
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>
<script src="/static/base/js/ajaxfileupload.js"></script>

<script type="text/javascript">
jQuery(function($) {
	$("#file-banner").on('change', function(){
		var fileId = 'file-banner';
		$.ajaxFileUpload({
		   url: '/home/upload/image/fid/'+fileId,
           secureuri: false,
           fileElementId: fileId,
           dataType: 'json',// 上传完成后, 返回json, text
           success: function(res) { // 上传之后回调
               if(res.result == 1){
                   $("#banner-img").attr('src', res.extension);
                   $("#banner").val(res.extension);
                   $("#delete").removeClass('hidden').siblings('#' + fileId).addClass('hidden');
               }else{
                   bootbox.alert(res.message);
               }
           }
		});
	});
	// 删除图片
	$("#delete").on('click', function(){
		var path = $("#banner").val();
		if (path == '') return false;
		$.ajax({
            type: "POST",
            url : "/home/upload/deleteimage",
            data : {path:path},
            dataType: "json",
            success : function(res){
                if(res.result == 1){
                	$("#banner-img").attr('src', 'http://placehold.it/120x120');
                    $("#banner").val('');
                    $("#delete").addClass('hidden').siblings('#file-banner').removeClass('hidden');
                }else{
                    bootbox.alert(res.message);
                    return false;
                }
            }
        });
	});

	// 保存
	$("#submit").on('click', function(){
		$.ajax({
            type: "POST",
            url : "/home/apps/appsave",
            data : $('#save').serialize(),
            dataType: "json",
            success : function(res){
                if(res.result == 1){
                    bootbox.alert(res.message,function(){
                        window.location.href = '/home/apps/index/';
                    });
                }else{
                    bootbox.alert(res.message);
                    return false;
                }
            }
        });
	});
});
</script>